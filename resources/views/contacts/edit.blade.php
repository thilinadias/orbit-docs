<x-app-layout>
    <x-slot name="header">
        Edit Contact: {{ $contact->first_name }} {{ $contact->last_name }}
    </x-slot>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form action="{{ route('contacts.update', [$organization->slug, $contact->id]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid gap-6 mb-4 md:grid-cols-2">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">First Name</span>
                    <input name="first_name" value="{{ $contact->first_name }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" required />
                </label>
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Last Name</span>
                    <input name="last_name" value="{{ $contact->last_name }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
            </div>

            <div class="grid gap-6 mb-4 md:grid-cols-2">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Title</span>
                    <input name="title" value="{{ $contact->title }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Department</span>
                    <input name="department" value="{{ $contact->department }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
            </div>

            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Email</span>
                    <input type="email" name="email" value="{{ $contact->email }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
            </div>

            <div class="grid gap-6 mb-4 md:grid-cols-2">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Mobile Phone</span>
                    <input name="phone_mobile" value="{{ $contact->phone_mobile }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Office Phone</span>
                    <input name="phone_office" value="{{ $contact->phone_office }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
            </div>

            <div class="flex gap-6 mb-4">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="is_vip" value="1" {{ $contact->is_vip ? 'checked' : '' }} class="form-checkbox text-purple-600 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" />
                    <span class="text-gray-700 dark:text-gray-400 text-sm">VIP/Executive</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="is_primary" value="1" {{ $contact->is_primary ? 'checked' : '' }} class="form-checkbox text-purple-600 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" />
                    <span class="text-gray-700 dark:text-gray-400 text-sm">Primary Contact</span>
                </label>
            </div>

            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Notes</span>
                    <textarea name="notes" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3">{{ $contact->notes }}</textarea>
                </label>
            </div>

            <div class="mt-4">
                <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Update Contact
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
