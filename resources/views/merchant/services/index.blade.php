<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Manage Services') }}
            </h2>
            <a href="{{ route('merchant.services.create') }}" class="px-5 py-2.5 bg-pink-600 text-white rounded-xl font-medium hover:bg-pink-700 transition shadow-lg shadow-pink-600/20 text-sm">
                + Add New Service
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($services as $service)
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col justify-between">
                        <div>
                            @if($service->image)
                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-50 flex items-center justify-center text-gray-400 text-sm font-medium">No Image</div>
                            @endif
                            <div class="p-6">
                                <h3 class="font-bold text-lg text-gray-900">{{ $service->name }}</h3>
                                <p class="text-pink-600 font-bold mt-1">KSh {{ number_format($service->price, 2) }}</p>
                                <p class="text-gray-500 text-sm mt-3 line-clamp-2 leading-relaxed">{{ $service->description }}</p>
                            </div>
                        </div>
                        <div class="px-6 pb-6 pt-0 flex items-center justify-between border-t border-gray-50 mt-4 pt-4">
                            <a href="{{ route('merchant.services.edit', $service->id) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900 transition">Edit</a>
                            <form action="{{ route('merchant.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-900 transition">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-16 bg-white rounded-3xl border border-gray-100 shadow-sm">
                        <p class="text-gray-500 text-sm">No services added yet. Click "+ Add New Service" to get started.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>