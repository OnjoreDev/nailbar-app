<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'NailPro SaaS') }} - Smart Booking for Nail Technicians</title>
        
        <!-- Fonts & Tailwind CSS via Vite -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 text-gray-900 antialiased font-sans selection:bg-pink-500 selection:text-white">

        <!-- Navigation Bar -->
        <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-pink-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-pink-600/20">
                        💅
                    </div>
                    <span class="font-bold text-xl tracking-tight text-gray-900">Nail<span class="text-pink-600">Pro</span></span>
                </div>

                @if (Route::has('login'))
                    <nav class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-xl bg-gray-900 text-white font-medium text-sm hover:bg-gray-800 transition shadow-sm">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-gray-600 font-medium text-sm hover:text-gray-950 transition">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-pink-600 text-white font-medium text-sm hover:bg-pink-700 transition shadow-lg shadow-pink-600/20">
                                    Get Started Free
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative pt-20 pb-32 overflow-hidden">
            <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-pink-50 border border-pink-100 text-pink-700 text-xs font-semibold mb-6">
                    <span class="w-2 h-2 rounded-full bg-pink-600 animate-pulse"></span>
                    Built for Independent Nail Artists & Beauty Studios
                </div>
                <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-gray-900 max-w-3xl mx-auto leading-tight">
                    Manage your nail studio bookings with <span class="text-pink-600">absolute ease.</span>
                </h1>
                <p class="mt-6 text-lg text-gray-600 max-w-xl mx-auto">
                    Get your own custom booking link, enforce a strict 10-client daily limit, and collect automated M-Pesa deposits instantly.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-pink-600 text-white font-semibold shadow-xl shadow-pink-600/30 hover:bg-pink-700 transition">
                            Go to Dashboard &rarr;
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-pink-600 text-white font-semibold shadow-xl shadow-pink-600/30 hover:bg-pink-700 transition">
                            Create Your Booking Page
                        </a>
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-white border border-gray-200 text-gray-700 font-semibold hover:bg-gray-50 transition">
                            Merchant Login
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Background Decorative Elements -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-pink-200/40 rounded-full blur-3xl -z-10 pointer-events-none"></div>
        </section>

        <!-- Features Grid Section -->
        <section class="py-20 bg-white border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900">Everything you need to run your studio</h2>
                    <p class="mt-2 text-gray-600">Designed specifically to eliminate scheduling conflicts and secure your daily revenue.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100">
                        <div class="w-12 h-12 rounded-2xl bg-pink-100 text-pink-600 flex items-center justify-center text-xl font-bold mb-6">🔗</div>
                        <h3 class="text-xl font-semibold text-gray-900">Custom SaaS Link</h3>
                        <p class="mt-2 text-gray-600 text-sm leading-relaxed">Share your unique custom booking link on Instagram, WhatsApp, and TikTok for clients to book directly.</p>
                    </div>

                    <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100">
                        <div class="w-12 h-12 rounded-2xl bg-pink-100 text-pink-600 flex items-center justify-center text-xl font-bold mb-6">📅</div>
                        <h3 class="text-xl font-semibold text-gray-900">Strict 10-Client Cap</h3>
                        <p class="mt-2 text-gray-600 text-sm leading-relaxed">Never get overworked. The system automatically locks dates once your daily capacity of 10 bookings is reached.</p>
                    </div>

                    <div class="p-8 rounded-3xl bg-gray-50 border border-gray-100">
                        <div class="w-12 h-12 rounded-2xl bg-pink-100 text-pink-600 flex items-center justify-center text-xl font-bold mb-6">💳</div>
                        <h3 class="text-xl font-semibold text-gray-900">M-Pesa Deposits</h3>
                        <p class="mt-2 text-gray-600 text-sm leading-relaxed">Secure your schedule with automated KSh 500 deposit payments via STK push or verified cash check-ins.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-50 border-t border-gray-200 py-12 text-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} NailPro SaaS. Built with Laravel & Tailwind CSS.</p>
        </footer>

    </body>
</html>