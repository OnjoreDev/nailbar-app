<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Waiting for Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8 text-center">
            <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 space-y-6">
                <!-- Animated Spinner -->
                <div class="relative w-20 h-20 mx-auto">
                    <div class="absolute inset-0 rounded-full border-4 border-green-100"></div>
                    <div class="absolute inset-0 rounded-full border-4 border-green-600 border-t-transparent animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center text-2xl">📲</div>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-gray-900">STK Push Sent!</h3>
                    <p class="text-sm text-gray-500 mt-2">
                        Please check your phone and enter your M-Pesa PIN to complete the <span class="font-semibold text-gray-800">KSh {{ number_format($booking->deposit_amount, 2) }}</span> deposit.
                    </p>
                </div>

                <div class="p-4 bg-gray-50 rounded-2xl text-xs text-gray-400">
                    Booking Reference: <span class="font-bold text-gray-700">#{{ $booking->id }}</span><br>
                    Waiting for confirmation from Safaricom...
                </div>

                <div>
                    <a href="{{ route('tenant.booking.create', $booking->merchant->slug) }}" class="text-xs text-red-500 hover:underline">
                        Cancel or try another payment method
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Polling Script -->
    @push('scripts')
    <script>
        const bookingId = {{ $booking->id }};
        const statusUrl = "{{ route('booking.status', $booking->id) }}";

        const interval = setInterval(async () => {
            try {
                const response = await fetch(statusUrl);
                const data = await response.json();

                // Check your browser console (F12) to see this output
                console.log("Polling check status:", data);

                // If payment status becomes paid/confirmed, redirect to success page
                if (data.payment_status === 'paid' || data.status === 'confirmed') {
                    clearInterval(interval);
                    window.location.href = data.redirect_url;
                }
            } catch (error) {
                console.error('Polling error:', error);
            }
        }, 3000); // Check every 3 seconds
    </script>
    @endpush
</x-app-layout>