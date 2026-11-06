<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Edit Service: ') . $service->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 md:p-10 rounded-3xl shadow-sm border border-gray-100">
                <form action="{{ route('merchant.services.update', $service->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold uppercase text-gray-500 mb-2">Service Name</label>
                        <input type="text" name="name" value="{{ old('name', $service->name) }}" required class="w-full rounded-2xl border-gray-200 focus:border-pink-500 focus:ring-pink-500 text-sm p-3.5">
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase text-gray-500 mb-2">Price (KSh)</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $service->price) }}" required class="w-full rounded-2xl border-gray-200 focus:border-pink-500 focus:ring-pink-500 text-sm p-3.5">
                        @error('price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase text-gray-500 mb-2">Service Image</label>
                        @if($service->image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="w-28 h-28 object-cover rounded-2xl border border-gray-200 shadow-sm">
                            </div>
                        @endif
                        <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-5 file:rounded-2xl file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 border border-gray-200 rounded-2xl p-1.5">
                        <p class="text-xs text-gray-400 mt-1.5">Leave blank to keep the current image.</p>
                        @error('image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase text-gray-500 mb-2">Description</label>
                        <textarea name="description" rows="4" class="w-full rounded-2xl border-gray-200 focus:border-pink-500 focus:ring-pink-500 text-sm p-3.5">{{ old('description', $service->description) }}</textarea>
                        @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4">
                        <a href="{{ route('merchant.services.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-2xl font-semibold text-sm hover:bg-gray-200 transition">Cancel</a>
                        <button type="submit" class="px-6 py-3 bg-pink-600 text-white rounded-2xl font-semibold text-sm hover:bg-pink-700 transition shadow-lg shadow-pink-600/20">Update Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>