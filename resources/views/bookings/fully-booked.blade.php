<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Date Fully Booked') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8 text-center">
            <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 shadow-sm">
                ✕
            </div>
            <h3 class="text-3xl font-extrabold text-gray-900">Slots Filled!</h3>
            <p class="text-sm text-gray-500 mt-2">
                We are fully booked (Maximum 10 clients reached) for <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</span>.
            </p>

            <div class="mt-8">
                <a href="{{ route('tenant.booking.create', $merchant->slug) }}" class="inline-block px-6 py-3.5 bg-gray-900 text-white rounded-2xl font-semibold text-sm hover:bg-gray-800 transition shadow-md">
                    &larr; Choose Another Date
                </a>
            </div>
        </div>
    </div>
</x-app-layout>