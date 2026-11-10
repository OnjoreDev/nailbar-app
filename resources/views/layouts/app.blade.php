<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NailPro SaaS') }}</title>

        <!-- Fonts & Tailwind CSS via Vite -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 text-gray-900 antialiased font-sans selection:bg-pink-500 selection:text-white min-h-screen flex flex-col justify-between">

        <div>
            <!-- Unified Modern Navigation Bar -->
            <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-pink-600 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-pink-600/20">
                                💅
                            </div>
                            <span class="font-bold text-xl tracking-tight text-gray-900">Nail<span class="text-pink-600">Pro</span></span>
                        </a>
                    </div>

                    <!-- Navigation Links / User Dropdown Menu -->
                    <div class="flex items-center gap-4">
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-950 transition">
                            Dashboard
                        </a>
                        
                        @auth
                            <div class="relative ml-3" x-data="{ open: false }">
                                <button @click="open = ! open" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition text-sm font-semibold text-gray-700 focus:outline-none">
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown panel -->
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-lg border border-gray-100 py-2 z-50">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition">Profile Settings</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">Log Out</button>
                                    </form>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Page Heading -->
            @isset($header)
                <div class="bg-white border-b border-gray-100 shadow-sm">
                    <div class="max-w-7xl mx-auto py-8 px-6">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-8 text-center text-sm text-gray-500 mt-20">
            <p>&copy; {{ date('Y') }} NailPro SaaS. Built with Laravel & Tailwind CSS.</p>
        </footer>
@stack('scripts')
    </body>
</html>