<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Add New Service') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 md:p-10 rounded-3xl shadow-sm border border-gray-100">
                <form action="{{ route('merchant.services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold uppercase text-gray-500 mb-2">Service Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g., Acrylic Full Set" class="w-full rounded-2xl border-gray-200 focus:border-pink-500 focus:ring-pink-500 text-sm p-3.5">
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase text-gray-500 mb-2">Price (KSh)</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" required placeholder="2500" class="w-full rounded-2xl border-gray-200 focus:border-pink-500 focus:ring-pink-500 text-sm p-3.5">
                        @error('price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase text-gray-500 mb-2">Service Image</label>
                        <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-5 file:rounded-2xl file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 border border-gray-200 rounded-2xl p-1.5">
                        @error('image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase text-gray-500 mb-2">Description</label>
                        <textarea name="description" rows="4" placeholder="Briefly describe what is included..." class="w-full rounded-2xl border-gray-200 focus:border-pink-500 focus:ring-pink-500 text-sm p-3.5">{{ old('description') }}</textarea>
                        @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4">
                        <a href="{{ route('merchant.services.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-2xl font-semibold text-sm hover:bg-gray-200 transition">Cancel</a>
                        <button type="submit" class="px-6 py-3 bg-pink-600 text-white rounded-2xl font-semibold text-sm hover:bg-pink-700 transition shadow-lg shadow-pink-600/20">Save Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>