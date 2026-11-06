<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ $merchant->business_name }} - Bookings
                </h2>
                <p class="text-sm text-gray-500 mt-1">Manage daily bookings and client statuses.</p>
            </div>
            <a href="{{ route('tenant.booking.create', $merchant->slug) }}" target="_blank" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                🌐 View Public Booking Link
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Date Filter & Daily Cap Tracker -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
                <form method="GET" action="{{ route('merchant.dashboard') }}" class="flex items-center gap-3 w-full md:w-auto">
                    <label class="text-sm font-medium text-gray-700">Filter Date:</label>
                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="rounded-xl border-gray-200 text-sm focus:border-pink-500 focus:ring-pink-500">
                </form>

                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-500">Capacity Today:</span>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold {{ $totalForDay >= 10 ? 'bg-red-100 text-red-700' : 'bg-pink-100 text-pink-700' }}">
                        {{ $totalForDay }} / 10 Clients Booked
                    </span>
                </div>
            </div>

            <!-- Bookings List Table -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900">Bookings for {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</h3>
                    <a href="{{ route('merchant.services.index') }}" class="text-xs font-semibold text-pink-600 hover:text-pink-700">Manage Services &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="p-4 pl-6">Client</th>
                                <th class="p-4">Service</th>
                                <th class="p-4">Deposit</th>
                                <th class="p-4">Payment</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 pr-6">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="p-4 pl-6">
                                        <p class="font-semibold text-gray-900">{{ $booking->client_name }}</p>
                                        <p class="text-xs text-gray-400">{{ $booking->client_phone }}</p>
                                    </td>
                                    <td class="p-4 font-medium text-gray-800">{{ $booking->service->name }}</td>
                                    <td class="p-4 font-semibold text-pink-600">KSh {{ number_format($booking->deposit_amount, 2) }}</td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium capitalize {{ $booking->payment_status === 'paid' ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                                            {{ $booking->payment_status }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium capitalize {{ $booking->status === 'confirmed' ? 'bg-blue-50 text-blue-700' : ($booking->status === 'completed' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600') }}">
                                            {{ $booking->status }}
                                        </span>
                                    </td>
                                    <td class="p-4 pr-6">
                                        <form action="{{ route('merchant.bookings.update-status', $booking->id) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="text-xs rounded-lg border-gray-200 py-1 px-2 focus:ring-pink-500">
                                                <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            <input type="hidden" name="payment_status" value="{{ $booking->payment_status }}">
                                            <button type="submit" class="px-3 py-1 bg-gray-900 text-white rounded-lg text-xs hover:bg-gray-800 transition">Save</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-gray-400 text-sm">
                                        No bookings scheduled for this date yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>