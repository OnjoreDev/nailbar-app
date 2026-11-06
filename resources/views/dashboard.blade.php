<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Merchant Dashboard') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Welcome back, {{ Auth::user()->name }}! Here is an overview of your studio.</p>
            </div>
            
            @if(Auth::user()->merchant)
                <div class="flex items-center gap-3">
                    <a href="{{ route('tenant.booking.create', Auth::user()->merchant->slug) }}" target="_blank" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                        <span>🌐 View Booking Page</span>
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Stats Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Today's Bookings</p>
                        <h3 class="text-3xl font-bold text-gray-900 mt-1">0 <span class="text-sm font-normal text-gray-400">/ 10 max</span></h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center text-xl">📅</div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Active Services</p>
                        <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ Auth::user()->merchant ? Auth::user()->merchant->services()->count() : 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">✨</div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pending Deposits</p>
                        <h3 class="text-3xl font-bold text-gray-900 mt-1">KSh 0</h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center text-xl">💰</div>
                </div>
            </div>

            <!-- Quick Action Cards / Management Links -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="{{ route('merchant.services.index') }}" class="p-5 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-pink-50/50 hover:border-pink-200 transition group flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-pink-600 font-bold group-hover:scale-110 transition">💅</div>
                        <div>
                            <h4 class="font-semibold text-gray-900 group-hover:text-pink-600 transition">Manage Services</h4>
                            <p class="text-xs text-gray-500">Add or edit nail styles & prices</p>
                        </div>
                    </a>

                    <a href="{{ route('merchant.dashboard') }}" class="p-5 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-pink-50/50 hover:border-pink-200 transition group flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-pink-600 font-bold group-hover:scale-110 transition">📋</div>
                        <div>
                            <h4 class="font-semibold text-gray-900 group-hover:text-pink-600 transition">View Daily Schedule</h4>
                            <p class="text-xs text-gray-500">Check daily bookings and limits</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>