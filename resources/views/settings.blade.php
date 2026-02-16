<x-app-layout>
    <x-slot name="header">
        System Settings
    </x-slot>

    <div class="p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <h3 class="mb-4 text-lg font-semibold text-gray-700 dark:text-gray-200">
            System Personalization
        </h3>

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid gap-6 md:grid-cols-2">
                <!-- System Name -->
                <div class="mb-4">
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">System Name</span>
                        <input name="system_name" value="{{ \App\Models\Setting::get('system_name', 'OrbitDocs') }}" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-input focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" placeholder="OrbitDocs" />
                    </label>
                </div>

                <!-- Sidebar Color -->
                <div class="mb-4">
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Sidebar Highlight Color</span>
                        <input type="color" name="sidebar_color" value="{{ \App\Models\Setting::get('sidebar_color', '#7e3af2') }}" class="block w-full mt-1 h-10 rounded-md border-gray-600 dark:bg-gray-700" />
                    </label>
                </div>

                <!-- System Logo -->
                <div class="mb-4 md:col-span-2">
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">System Logo</span>
                        <input type="file" name="system_logo" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-input focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" />
                    </label>
                    @if($logo = \App\Models\Setting::get('system_logo'))
                        <div class="mt-2">
                            <p class="text-xs text-gray-500 mb-1">Current Logo:</p>
                            <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="h-12 w-auto bg-gray-100 p-2 rounded">
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
