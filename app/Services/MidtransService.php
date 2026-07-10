<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
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

        Log::info('Midtrans status update', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
        ]);

        $updateData = array_filter([
            'midtrans_transaction_id' => $transactionId,
            'payment_method' => $paymentType ?? $payment->payment_method,
        ]);

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept' || $fraudStatus === null) {
                $this->markAsPaid($payment, $booking, $updateData);
            }
        } elseif ($transactionStatus === 'settlement') {
            $this->markAsPaid($payment, $booking, $updateData);
        } elseif ($transactionStatus === 'pending') {
            $payment->update(array_merge($updateData, ['payment_status' => 'pending']));
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel', 'failure'])) {
            $payment->update(array_merge($updateData, ['payment_status' => 'failed']));
            if ($booking->status !== 'cancelled') {
                $booking->update(['status' => 'cancelled']);
                if ($booking->flight) {
                    $booking->flight->increment('available_seats', $booking->total_passengers);
                }
                if ($booking->return_flight_id && $booking->returnFlight) {
                    $booking->returnFlight->increment('available_seats', $booking->total_passengers);
                }
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
        $payment->update(array_merge($extra, [
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]));

        $booking->update(['status' => 'confirmed']);
    }
}
