<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <span>Add Credential</span>
            <a href="{{ route('credentials.index', $organization->slug) }}" class="px-4 py-2 bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-300 dark:hover:bg-gray-600">
                &larr; Back to Credentials
            </a>
        </div>
    </x-slot>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form action="{{ route('credentials.store', $organization->slug) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Title</span>
                    <input name="title" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Domain Admin" />
                </label>
            </div>

            <div class="grid gap-6 mb-4 md:grid-cols-2">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Username</span>
                    <input name="username" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="admin" />
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Password</span>
                    <input type="password" name="password" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="********" />
                </label>
            </div>

            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">URL</span>
                    <input name="url" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="https://portal.azure.com" />
                </label>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Linked Asset (Optional)</span>
                    <select name="asset_id" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option value="">None</option>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}">{{ $asset->name }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Visibility</span>
                    <select name="visibility" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option value="org" {{ old('visibility') == 'org' ? 'selected' : '' }}>Organization (Default)</option>
                        <option value="restricted" {{ old('visibility') == 'restricted' ? 'selected' : '' }}>Restricted (Admins Only)</option>
                    </select>
                </label>
            </div>

            <div class="mt-4">
                <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Save Credential
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
