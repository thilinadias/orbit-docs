<x-installer-layout>
    <form action="{{ route('install.storeDatabase') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Database Setup</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure your database connection.</p>
        </div>

        <div>
            <label for="db_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DB Host</label>
            <div class="mt-1">
                <input type="text" name="db_host" id="db_host" value="{{ old('db_host', 'orbitdocs-db') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
            <p class="mt-1 text-xs text-gray-500">Default for Docker: orbitdocs-db</p>
        </div>

        <div>
            <label for="db_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DB Port</label>
            <div class="mt-1">
                <input type="text" name="db_port" id="db_port" value="{{ old('db_port', '3306') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
        </div>

        <div>
            <label for="db_database" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Database Name</label>
            <div class="mt-1">
                <input type="text" name="db_database" id="db_database" value="{{ old('db_database', 'orbitdocs') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
        </div>

        <div>
            <label for="db_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
            <div class="mt-1">
                <input type="text" name="db_username" id="db_username" value="{{ old('db_username', 'orbitdocs') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
        </div>

        <div>
            <label for="db_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
            <div class="mt-1">
                <input type="password" name="db_password" id="db_password" value="{{ old('db_password', 'secret') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
            </div>
        </div>

        <button type="submit" class="flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Test Connection & Save
        </button>
    </form>
</x-installer-layout>
