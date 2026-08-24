<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Service Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100 max-w-xl mx-auto">
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-200 dark:border-gray-700">
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 w-1/3">Name</th>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $service->name }}</td>
                            </tr>
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700">Price</th>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $service->price }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Back Button -->
                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('services.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:bg-gray-400 dark:focus:bg-gray-600 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Back to List
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>