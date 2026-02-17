<x-installer-layout>
    <x-slot name="title">
        Create Organization
    </x-slot>

    <p class="mb-4 text-gray-600 dark:text-gray-400">
        Create your first organization to get started. You will be automatically assigned as the administrator.
    </p>

    <form method="POST" action="{{ route('install.storeOrganization') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Organization Name</span>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                    placeholder="My Company" />
                @error('name')
                    <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                @enderror
            </label>
        </div>

        <div class="mb-4">
            <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Organization Slug (URL)</span>
                <input type="text" name="slug" value="{{ old('slug') }}" required
                    class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                    placeholder="my-company" />
                <span class="text-xs text-gray-500 dark:text-gray-400">This will be used in your URL: {{ url('/') }}/[slug]/dashboard</span>
                @error('slug')
                    <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                @enderror
            </label>
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit"
                class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                Create Organization & Continue
            </button>
        </div>
    </form>
</x-installer-layout>
