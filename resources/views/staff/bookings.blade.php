<x-staff-layout title="Bookings - drgMaskapai" header="Manage Bookings">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Booking Code</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Flight</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Passengers</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-slate-900">{{ $booking->booking_code }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-slate-900">{{ $booking->user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $booking->user->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-slate-900">{{ $booking->flight->flight_number }}</p>
                            <p class="text-xs text-slate-500">{{ $booking->flight->departureAirport->iata_code }} → {{ $booking->flight->arrivalAirport->iata_code }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-600">{{ $booking->total_passengers }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-slate-900">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($booking->payment)
                                @php
                                    $payColors = ['paid' => 'bg-green-100 text-green-700', 'pending' => 'bg-amber-100 text-amber-700', 'failed' => 'bg-red-100 text-red-700'];
                                    $payColor = $payColors[$booking->payment->payment_status] ?? 'bg-slate-100 text-slate-600';
                                @endphp
                                <span class="px-2 py-1 text-xs font-bold rounded-full {{ $payColor }}">{{ ucfirst($booking->payment->payment_status) }}</span>
                            @else
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-600">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'confirmed' => 'bg-green-100 text-green-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                    'cancelled' => 'bg-red-100 text-red-700'
                                ];
                                $statusLabels = [
                                    'pending' => 'Pending Payment',
                                    'confirmed' => 'Confirmed',
                                    'in_progress' => 'In Progress',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled'
                                ];
                                $color = $statusColors[$booking->status] ?? 'bg-slate-100 text-slate-700';
                                $label = $statusLabels[$booking->status] ?? ucfirst($booking->status);
                            @endphp
                            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $color }}">{{ $label }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('staff.booking.show', $booking) }}" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition">View</a>
                                @if($booking->status === 'pending')
                                    <form action="{{ route('staff.booking.updateStatus', $booking) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition" onclick="return confirm('Approve booking?')">Approve</button>
                                    </form>
                                    <form action="{{ route('staff.booking.updateStatus', $booking) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition" onclick="return confirm('Cancel booking?')">Cancel</button>
                                    </form>
                                @endif
                                @if($booking->payment && $booking->payment->payment_status === 'pending')
                                    <form action="{{ route('staff.booking.verifyPayment', $booking) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition" onclick="return confirm('Verify payment?')">Verify</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-6 py-8 text-center text-slate-500 text-sm">Belum ada booking</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</x-staff-layout>