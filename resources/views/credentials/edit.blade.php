<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <span>Edit Credential</span>
            <a href="{{ route('credentials.show', [$currentOrganization->slug, $credential->id]) }}" class="px-4 py-2 bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-300 dark:hover:bg-gray-600">
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form action="{{ route('credentials.update', [$currentOrganization->slug, $credential->id]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Title</span>
                    <input name="title" value="{{ old('title', $credential->title) }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" required />
                </label>
            </div>

            <div class="grid gap-6 mb-4 md:grid-cols-2">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Username</span>
                    <input name="username" value="{{ old('username', $credential->username) }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Password (Leave blank to keep unchanged)</span>
                    <input type="password" name="password" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="********" />
                </label>
            </div>

            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">URL</span>
                    <input name="url" value="{{ old('url', $credential->url) }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Linked Asset (Optional)</span>
                    <select name="asset_id" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option value="">None</option>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" {{ old('asset_id', $credential->asset_id) == $asset->id ? 'selected' : '' }}>{{ $asset->name }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Notes</span>
                    <textarea name="notes" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3">{{ old('notes', $credential->notes) }}</textarea>
                </label>
            </div>

            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Visibility</span>
                    <select name="visibility" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option value="org" {{ old('visibility', $credential->visibility) == 'org' ? 'selected' : '' }}>Organization (Default)</option>
                        <option value="restricted" {{ old('visibility', $credential->visibility) == 'restricted' ? 'selected' : '' }}>Restricted (Admins Only)</option>
                    </select>
                </label>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Update Credential
                </button>
            </div>
        </form>
        
        <div class="mt-4 border-t pt-4 border-gray-200 dark:border-gray-700">
             <form action="{{ route('credentials.destroy', [$currentOrganization->slug, $credential->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this credential?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-red">
                    Delete Credential
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
