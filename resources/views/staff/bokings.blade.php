<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-black">Manage Bookings</h2>
                <p class="text-sm text-slate-500 mt-1">{{ now()->format('l, d F Y') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Booking Code</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Flight</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Passengers</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Total</th>
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
                                    <span class="text-sm font-bold text-slate-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($booking->status === 'confirmed')
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Confirmed</span>
                                    @elseif($booking->status === 'pending')
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">Pending</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full">Cancelled</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('staff.booking.show', $booking) }}" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded">View</a>
                                        @if($booking->status === 'pending')
                                            <form action="{{ route('staff.booking.updateStatus', $booking) }}" method="POST" class="inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded" onclick="return confirm('Approve booking?')">Approve</button>
                                            </form>
                                            <form action="{{ route('staff.booking.updateStatus', $booking) }}" method="POST" class="inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded" onclick="return confirm('Cancel booking?')">Cancel</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="px-6 py-8 text-center text-slate-500 text-sm">Belum ada booking</td></tr>
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
        </div>
    </div>
</x-admin-layout>
