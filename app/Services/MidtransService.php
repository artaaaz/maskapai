<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Seat;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized', true);
        Config::$is3ds = config('services.midtrans.is_3ds', true);
    }

    public function handleNotification(): array
    {
        $notif = new Notification();
        
        $serverKey = config('services.midtrans.server_key');
        $orderId = $notif->order_id;
        $statusCode = $notif->status_code;
        $grossAmount = $notif->gross_amount;
        $signatureKey = $notif->signature_key;

        $localSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($localSignature !== $signatureKey) {
            Log::error('Midtrans signature verification failed', [
                'order_id' => $orderId,
                'received_signature' => $signatureKey,
                'calculated_signature' => $localSignature,
            ]);
            return ['success' => false, 'message' => 'Invalid signature key'];
        }

        return $this->processStatus(
            $orderId,
            $notif->transaction_status,
            $notif->fraud_status ?? null,
            $notif->transaction_id ?? null,
            $notif->payment_type ?? null
        );
    }

    public function syncBookingPayment(Booking $booking): array
    {
        // JANGAN sync jika payment sudah paid - hindari overwrite status
        if ($booking->payment && $booking->payment->payment_status === 'paid') {
            Log::info('Midtrans sync skipped: payment already paid for ' . $booking->booking_code);
            return [
                'success' => true,
                'payment_status' => 'paid',
                'booking_status' => $booking->status,
            ];
        }

        try {
            $status = Transaction::status($booking->booking_code);

            return $this->processStatus(
                $status->order_id,
                $status->transaction_status,
                $status->fraud_status ?? null,
                $status->transaction_id ?? null,
                $status->payment_type ?? null
            );
        } catch (\Exception $e) {
            Log::warning('Midtrans sync failed for ' . $booking->booking_code . ': ' . $e->getMessage());

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function processStatus(
        string $orderId,
        string $transactionStatus,
        ?string $fraudStatus,
        ?string $transactionId,
        ?string $paymentType
    ): array {
        $booking = Booking::where('booking_code', $orderId)->first();

        if (!$booking) {
            Log::error('Midtrans: booking not found for order ' . $orderId);

            return ['success' => false, 'message' => 'Booking not found'];
        }

        $payment = $booking->payment;

        if (!$payment) {
            Log::error('Midtrans: payment not found for booking ' . $booking->booking_code);

            return ['success' => false, 'message' => 'Payment not found'];
        }

        // ===== GUARD: Jangan overwrite status PAID =====
        // Jika payment sudah paid, abaikan update apapun dari Midtrans
        // kecuali refund/chargeback yang jelas-jelas berbeda.
        if ($payment->payment_status === 'paid') {
            Log::info('Midtrans processStatus skipped: payment already paid for ' . $booking->booking_code, [
                'incoming_status' => $transactionStatus,
            ]);
            return [
                'success' => true,
                'payment_status' => 'paid',
                'booking_status' => $booking->status,
            ];
        }

        $updateData = array_filter([
            'midtrans_transaction_id' => $transactionId,
            'payment_method' => $paymentType ?? $payment->payment_method,
        ]);

        // ===== GUARD: Jangan overwrite status operasional =====
        // Jika booking sudah dalam status operasional (checked_in, boarded, dll),
        // jangan ubah status booking, tapi pastikan status payment diupdate jika perlu.
        if (in_array($booking->status, ['checked_in', 'boarded', 'in_progress', 'completed'])) {
            Log::info('Midtrans processStatus: booking is already in operational state ' . $booking->status, [
                'booking_code' => $booking->booking_code,
                'incoming_status' => $transactionStatus,
            ]);

            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                $payment->updateStatusSafely('paid', true, $updateData);
            }

            return [
                'success' => true,
                'payment_status' => $payment->fresh()->payment_status,
                'booking_status' => $booking->status,
            ];
        }

        // ===== GUARD: Jangan overwrite booking CONFIRMED dengan cancel/expire jika payment sudah paid =====
        if ($booking->status === 'confirmed' && in_array($transactionStatus, ['deny', 'expire', 'cancel', 'failure']) && $payment->payment_status === 'paid') {
            Log::warning('Midtrans processStatus: booking already confirmed and paid, ignoring status ' . $transactionStatus, [
                'booking_code' => $booking->booking_code,
            ]);
            return [
                'success' => true,
                'payment_status' => $payment->payment_status,
                'booking_status' => 'confirmed',
            ];
        }

        Log::info('Midtrans status update', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
        ]);

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept' || $fraudStatus === null) {
                $this->markAsPaid($payment, $booking, $updateData);
            }
        } elseif ($transactionStatus === 'settlement') {
            $this->markAsPaid($payment, $booking, $updateData);
        } elseif ($transactionStatus === 'pending') {
            $payment->updateStatusSafely('pending', false, $updateData);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel', 'failure'])) {
            $payment->updateStatusSafely('failed', false, $updateData);
            if (in_array($booking->status, ['pending', 'confirmed'])) {
                $booking->update(['status' => 'cancelled']);
                
                // Release seats back to available
                $this->releaseSeats($booking);
            }
        }

        return [
            'success' => true,
            'payment_status' => $payment->fresh()->payment_status,
            'booking_status' => $booking->fresh()->status,
        ];
    }

    private function markAsPaid(Payment $payment, Booking $booking, array $extra = []): void
    {
        $payment->updateStatusSafely('paid', true, $extra);

        // Langsung set ke 'confirmed' dalam satu update (pending -> confirmed
        // adalah transisi valid di Booking::isValidTransition). Sebelumnya kode
        // ini melakukan dua kali update (status='paid' lalu status='confirmed'),
        // yang tidak perlu dan hanya menambah query serta state transisi sesaat
        // yang tidak berguna.
        $booking->update(['status' => 'confirmed']);

        // Lock seats in the seats table for this flight
        $this->lockSeats($booking);
    }

    /**
     * Lock seats (mark as booked) when payment is successful
     */
    private function lockSeats(Booking $booking): void
    {
        $bookedSeatNumbers = $booking->passengers()
            ->whereNotNull('seat_number')
            ->pluck('seat_number')
            ->toArray();

        if (!empty($bookedSeatNumbers) && $booking->flight) {
            Seat::where('airplane_id', $booking->flight->airplane_id)
                ->whereIn('seat_number', $bookedSeatNumbers)
                ->update([
                    'status' => 'booked',
                    'booking_id' => $booking->id,
                ]);

            Log::info('Seats locked for booking ' . $booking->booking_code, [
                'seats' => $bookedSeatNumbers,
            ]);
        }
    }

    /**
     * Release seats back to available when booking is cancelled/expired
     */
    private function releaseSeats(Booking $booking): void
    {
        $bookedSeatNumbers = $booking->passengers()
            ->whereNotNull('seat_number')
            ->pluck('seat_number')
            ->toArray();

        if (!empty($bookedSeatNumbers) && $booking->flight) {
            Seat::where('airplane_id', $booking->flight->airplane_id)
                ->whereIn('seat_number', $bookedSeatNumbers)
                ->update([
                    'status' => 'available',
                    'booking_id' => null,
                ]);

            Log::info('Seats released for booking ' . $booking->booking_code, [
                'seats' => $bookedSeatNumbers,
            ]);
        }
    }
}