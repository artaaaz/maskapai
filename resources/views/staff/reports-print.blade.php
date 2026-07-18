<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Operasional - drgMaskapai</title>
    <style>
        @page { margin: 20mm 15mm; }
        * { font-family: 'Segoe UI', Arial, sans-serif; box-sizing: border-box; }
        body { color: #1e293b; font-size: 11px; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #14532d; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { color: #14532d; margin: 0 0 5px; font-size: 20px; }
        .header p { color: #64748b; margin: 0; font-size: 11px; }
        .staff-info { text-align: right; font-size: 10px; color: #64748b; margin-bottom: 15px; }
        .summary { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 25px; }
        .summary-item { flex: 1; min-width: 120px; padding: 8px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; border-left: 3px solid #14532d; }
        .summary-item .label { font-size: 9px; text-transform: uppercase; color: #64748b; font-weight: 700; }
        .summary-item .value { font-size: 16px; font-weight: 700; color: #14532d; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 10px; }
        th { background: #14532d; color: white; padding: 8px 6px; text-align: left; font-size: 9px; text-transform: uppercase; }
        td { padding: 6px; border-bottom: 1px solid #e2e8f0; }
        tr:nth-child(even) { background: #f8fafc; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 9px; font-weight: 600; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-amber { background: #fef3c7; color: #92400e; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-slate { background: #f1f5f9; color: #475569; }
        .footer { text-align: center; color: #94a3b8; font-size: 9px; margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0; }
        .section-title { font-size: 13px; font-weight: 700; color: #14532d; margin: 20px 0 10px; }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align:center;margin-bottom:20px;">
        <button onclick="window.print()" style="padding:10px 30px;background:#14532d;color:white;border:none;border-radius:8px;font-size:14px;cursor:pointer;font-weight:600;">🖨️ Cetak Laporan</button>
        <button onclick="window.close()" style="padding:10px 30px;background:#e2e8f0;color:#475569;border:none;border-radius:8px;font-size:14px;cursor:pointer;font-weight:600;margin-left:10px;">✕ Tutup</button>
    </div>

    <div class="header">
        <h1>Laporan Operasional drgMaskapai</h1>
        <p>Periode: {{ request('date_from', 'Awal') }} - {{ request('date_to', 'Sekarang') }}</p>
    </div>

    <div class="staff-info">
        Dicetak oleh: {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})<br>
        Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="label">Total Booking</div>
            <div class="value">{{ $totalBookings }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Total Penumpang</div>
            <div class="value">{{ $totalPassengers }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Total Revenue</div>
            <div class="value">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Pending Payment</div>
            <div class="value">{{ $pendingPayments }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Confirmed Booking</div>
            <div class="value">{{ $confirmedBookings }}</div>
        </div>
    </div>

    <div class="section-title">Daftar Booking</div>

    @if($bookings->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Customer</th>
                <th>Flight</th>
                <th>Rute</th>
                <th>Pnp</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
            @php
                $statusColors = ['confirmed' => 'badge-green', 'pending' => 'badge-amber', 'cancelled' => 'badge-red'];
                $payColors = ['paid' => 'badge-green', 'pending' => 'badge-amber', 'failed' => 'badge-red'];
                $route = ($booking->flight->departureAirport->iata_code ?? '??') . '→' . ($booking->flight->arrivalAirport->iata_code ?? '??');
            @endphp
            <tr>
                <td style="font-weight:600;">{{ $booking->booking_code }}</td>
                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                <td>{{ $booking->flight->flight_number ?? 'N/A' }}</td>
                <td>{{ $route }}</td>
                <td style="text-align:center;">{{ $booking->total_passengers }}</td>
                <td style="font-weight:600;">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                <td>
                    <span class="badge {{ $payColors[$booking->payment->payment_status ?? 'pending'] ?? 'badge-slate' }}">
                        {{ $booking->payment ? ucfirst($booking->payment->payment_status) : 'Belum' }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $statusColors[$booking->status] ?? 'badge-slate' }}">{{ ucfirst($booking->status) }}</span>
                </td>
                <td style="font-size:9px;">{{ $booking->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p style="text-align:center;padding:30px;color:#94a3b8;">Tidak ada data booking</p>
    @endif

    <div style="margin-top:20px;padding:12px;background:#f0fdf4;border-radius:6px;border:1px solid #bbf7d0;">
        <strong style="color:#166534;">Total Revenue: Rp{{ number_format($totalRevenue, 0, ',', '.') }}</strong>
    </div>

    <div class="footer">
        Generated by drgMaskapai - {{ now()->format('d/m/Y H:i') }}<br>
        © {{ now()->year }} drgMaskapai. All rights reserved.
    </div>

    <script>
        // Auto print on load
        setTimeout(function() {
            window.print();
        }, 500);
    </script>
</body>
</html>