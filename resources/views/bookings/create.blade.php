<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
               <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ $merchant->business_name ?? 'Nail Studio' }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ $merchant->bio ?? 'Choose your service and pick an available date.' }}</p>
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-pink-50 text-pink-700 rounded-full text-xs font-semibold">
                <span>📍 Daily Cap: Max 10 Clients</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 md:p-10 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Book Your Appointment</h3>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Monthly Availability Calendar Widget -->
                <div class="mb-8 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-bold text-gray-900 text-base">
                            {{ $startOfMonth->format('F Y') }} Availability
                        </h4>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-100 border border-green-300 inline-block"></span> Available</span>
                            <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-100 border border-red-300 inline-block"></span> Full (10/10)</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-7 gap-2 text-center text-xs font-semibold text-gray-400 mb-2">
                        <div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div><div>Sun</div>
                    </div>

                    <div class="grid grid-cols-7 gap-2">
                        @php
                            $daysInMonth = $startOfMonth->daysInMonth;
                            $startDayOfWeek = ($startOfMonth->dayOfWeekIso - 1); // 0 for Monday
                        @endphp

                        {{-- Blank padding spaces for offset days --}}
                        @for($i = 0; $i < $startDayOfWeek; $i++)
                            <div></div>
                        @endfor

                        @for($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $currentDate = $startOfMonth->copy()->day($day)->toDateString();
                                $count = $bookedCounts[$currentDate] ?? 0;
                                $isFull = $count >= 10;
                                $isPast = $currentDate < today()->toDateString();
                            @endphp

                            @if($isPast || $isFull)
                                {{-- Disabled/Unclickable for past or full dates --}}
                                <div class="p-2 rounded-2xl border text-center opacity-75 
                                    @if($isPast) bg-gray-50 text-gray-300 border-gray-100 cursor-not-allowed
                                    @elseif($isFull) bg-red-50 text-red-700 border-red-200 font-bold cursor-not-allowed @endif">
                                    <span class="block text-sm font-bold">{{ $day }}</span>
                                    <span class="block text-[10px] mt-0.5">
                                        @if($isPast) Past @else Fully Booked @endif
                                    </span>
                                </div>
                            @else
                                {{-- Clickable Available Date --}}
                                <div onclick="selectDate('{{ $currentDate }}')" 
                                    class="p-2 rounded-2xl border text-center cursor-pointer transition bg-green-50/50 text-green-800 border-green-200 hover:border-pink-500 hover:bg-pink-50 hover:text-pink-700 group">
                                    <span class="block text-sm font-bold group-hover:scale-105 transition-transform">{{ $day }}</span>
                                    <span class="block text-[10px] mt-0.5 text-green-600 group-hover:text-pink-600 font-medium">
                                        {{ 10 - $count }} slots left
                                    </span>
                                </div>
                            @endif
                        @endfor
                    </div>
                </div>

                <form action="{{ route('tenant.booking.check-availability', $merchant->slug) }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-500 mb-2">Your Name</label>
                            <input type="text" name="client_name" value="{{ old('client_name') }}" required placeholder="Jane Doe" class="w-full rounded-2xl border-gray-200 focus:border-pink-500 focus:ring-pink-500 text-sm p-3.5">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-500 mb-2">Phone Number</label>
                            <input type="text" name="client_phone" value="{{ old('client_phone') }}" required placeholder="0712345678" class="w-full rounded-2xl border-gray-200 focus:border-pink-500 focus:ring-pink-500 text-sm p-3.5">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase text-gray-500 mb-2">Email Address (Optional)</label>
                        <input type="email" name="client_email" value="{{ old('client_email') }}" placeholder="jane@example.com" class="w-full rounded-2xl border-gray-200 focus:border-pink-500 focus:ring-pink-500 text-sm p-3.5">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase text-gray-500 mb-3">Select Service</label>
                        <div class="space-y-3">
                            @forelse($services as $service)
                                <label class="relative flex items-center justify-between p-4 rounded-2xl border border-gray-200 hover:border-pink-300 bg-gray-50/50 cursor-pointer transition">
                                    <div class="flex items-center gap-4">
                                        <input type="radio" name="service_id" value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'checked' : '' }} required class="text-pink-600 focus:ring-pink-500 h-4 w-4">
                                        @if($service->image)
                                            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="w-12 h-12 rounded-xl object-cover">
                                        @endif
                                        <div>
                                            <p class="font-bold text-gray-900 text-sm">{{ $service->name }}</p>
                                            <p class="text-xs text-gray-500 line-clamp-1 mt-0.5">{{ $service->description }}</p>
                                        </div>
                                    </div>
                                    <span class="font-bold text-pink-600 text-sm">KSh {{ number_format($service->price, 2) }}</span>
                                </label>
                            @empty
                                <div class="p-6 text-center text-gray-400 text-sm border border-dashed border-gray-200 rounded-2xl">
                                    No services currently available for booking.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase text-gray-500 mb-2">Appointment Date</label>
                        <input type="date" name="booking_date" value="{{ old('booking_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required class="w-full rounded-2xl border-gray-200 focus:border-pink-500 focus:ring-pink-500 text-sm p-3.5">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase text-gray-500 mb-2">Special Notes</label>
                        <textarea name="notes" rows="3" placeholder="Any design preferences or special requests..." class="w-full rounded-2xl border-gray-200 focus:border-pink-500 focus:ring-pink-500 text-sm p-3.5">{{ old('notes') }}</textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-4 bg-pink-600 text-white rounded-2xl font-semibold text-sm hover:bg-pink-700 transition shadow-lg shadow-pink-600/20 text-center">
                            Proceed to Payment (KSh 500 Deposit) &rarr;
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function selectDate(dateString) {
            const dateInput = document.querySelector('input[name="booking_date"]');
            if (dateInput) {
                dateInput.value = dateString;
                dateInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                dateInput.classList.add('ring-2', 'ring-pink-500', 'border-pink-500');
                setTimeout(() => {
                    dateInput.classList.remove('ring-2', 'ring-pink-500');
                }, 1500);
            }
        }
    </script>
    @endpush
</x-app-layout>