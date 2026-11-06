<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Booking Confirmation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8 text-center">
            <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 shadow-sm">
                ✓
            </div>
            <h3 class="text-3xl font-extrabold text-gray-900">Booking Reserved!</h3>
            <p class="text-sm text-gray-500 mt-2">Thank you, <span class="font-semibold text-gray-800">{{ $booking->client_name }}</span>. Your appointment has been secured.</p>

            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 text-left mt-8 space-y-4">
                <div class="flex justify-between border-b border-gray-100 pb-3 text-sm">
                    <span class="text-gray-400 font-medium">Booking Reference</span>
                    <span class="font-bold text-gray-900">#{{ $booking->id }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-3 text-sm">
                    <span class="text-gray-400 font-medium">Service</span>
                    <span class="font-bold text-gray-900">{{ $booking->service->name }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-3 text-sm">
                    <span class="text-gray-400 font-medium">Appointment Date</span>
                    <span class="font-bold text-gray-900">{{ $booking->booking_date->format('F d, Y') }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-3 text-sm">
                    <span class="text-gray-400 font-medium">Deposit Amount</span>
                    <span class="font-bold text-pink-600">KSh {{ number_format($booking->deposit_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400 font-medium">Payment Status</span>
                    <span class="capitalize font-semibold text-xs px-3 py-1 rounded-full {{ $booking->payment_status === 'paid' ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                        {{ $booking->payment_status }}
                    </span>
                </div>
            </div>

            <div class="mt-8">
                <a href="{{ route('tenant.booking.create', $booking->merchant->slug) }}" class="inline-block px-6 py-3.5 bg-gray-900 text-white rounded-2xl font-semibold text-sm hover:bg-gray-800 transition shadow-md">
                    &larr; Back to {{ $booking->merchant->business_name }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>