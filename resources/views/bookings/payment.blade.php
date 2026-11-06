<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Complete Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Reservation Summary Card -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-4">Reservation Summary</h3>
                <div class="flex justify-between items-center pb-2">
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">{{ $service->name }}</h4>
                        <p class="text-xs text-gray-500 mt-1">Date: {{ \Carbon\Carbon::parse($draft['booking_date'])->format('F d, Y') }}</p>
                        <p class="text-xs text-gray-500">Client: {{ $draft['client_name'] }} ({{ $draft['client_phone'] }})</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-gray-400 block uppercase font-semibold">Deposit Due</span>
                        <span class="text-2xl font-extrabold text-pink-600">KSh 500.00</span>
                    </div>
                </div>
            </div>

            <!-- M-Pesa STK Push Box -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center font-bold text-xl shadow-sm">📲</div>
                    <div>
                        <h3 class="font-bold text-gray-900">Pay via M-Pesa Express</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Enter your Safaricom number to trigger an STK Push PIN prompt.</p>
                    </div>
                </div>

                <form action="{{ route('booking.initiate-stk') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold uppercase text-gray-500 mb-2">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $draft['client_phone']) }}" required placeholder="2547XXXXXXXX" class="w-full rounded-2xl border-gray-200 focus:border-green-500 focus:ring-green-500 text-sm p-3.5">
                    </div>
                    <button type="submit" class="w-full py-4 bg-green-600 text-white rounded-2xl font-semibold text-sm hover:bg-green-700 transition shadow-lg shadow-green-600/20">
                        Send M-Pesa Prompt & Reserve
                    </button>
                </form>
            </div>

            <!-- Cash Payment Option Box -->
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">Pay Cash on Arrival</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Pay the KSh 500 deposit directly at the studio.</p>
                </div>
                <form action="{{ route('booking.cash-checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-2xl font-semibold text-xs hover:bg-gray-200 transition">
                        Confirm Cash Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>