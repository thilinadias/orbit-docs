<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import Assets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <form action="{{ route('assets.import.process', $organization->slug) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- File Upload -->
                        <div class="mb-6">
                            <label for="csv_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                CSV File
                            </label>
                            <input type="file" name="csv_file" id="csv_file" accept=".csv" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" id="file_input_help">CSV (MAX. 2MB).</p>
                        </div>

                        <!-- Default Type Selection (Optional) -->
                        <div class="mb-6">
                            <label for="default_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Default Asset Type (Optional)
                            </label>
                            <input type="text" name="default_type" id="default_type" value="{{ $preselectedType ?? '' }}" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                placeholder="e.g. Server, Workstation (Used if 'type' column is missing)"
                            >
                            <p class="text-xs text-gray-500 mt-1">If the CSV column `type` is missing or empty, this value will be used.</p>
                        </div>
                        
                        <!-- Sample CSV Format info -->
                        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-700">
                            <h4 class="font-bold mb-2 text-sm uppercase">Expected CSV Format (Headers)</h4>
                            <code class="text-xs break-all">name, type, status, serial_number, manufacturer, model, ip_address, notes</code>
                            <ul class="mt-2 text-sm list-disc list-inside">
                                <li><strong>name</strong>: Required.</li>
                                <li><strong>type</strong>: Optional if "Default Asset Type" is set above.</li>
                                <li><strong>status</strong>: active, broken, archived (default: active).</li>
                            </ul>
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('assets.index', $organization->slug) }}" class="mr-3 px-4 py-2 bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-300 dark:hover:bg-gray-600">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Import Assets') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
