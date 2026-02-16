<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <span>{{ $contact->first_name }} {{ $contact->last_name }}</span>
            <a href="{{ route('contacts.edit', [$currentOrganization->slug, $contact->id]) }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                Edit
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-md dark:bg-gray-800 p-6">
        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">Contact Details</h4>
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Basic Info -->
            <div class="space-y-4">
                 <div class="flex flex-col">
                    <span class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">Full Name</span>
                    <span class="text-gray-700 dark:text-gray-300">{{ $contact->first_name }} {{ $contact->last_name }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">Title</span>
                    <span class="text-gray-700 dark:text-gray-300">{{ $contact->title ?? '-' }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">Department</span>
                    <span class="text-gray-700 dark:text-gray-300">{{ $contact->department ?? '-' }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">Location</span>
                    <span class="text-gray-700 dark:text-gray-300">{{ $contact->location ?? '-' }}</span>
                </div>
                 <div class="flex flex-col">
                    <span class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">Role/Access Level</span>
                    <span class="text-gray-700 dark:text-gray-300">{{ $contact->access_level ?? '-' }}</span>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="space-y-4">
                <div class="flex flex-col">
                    <span class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">Email</span>
                    <a href="mailto:{{ $contact->email }}" class="text-purple-600 dark:text-purple-400 hover:underline">{{ $contact->email ?? '-' }}</a>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">Mobile Phone</span>
                    <span class="text-gray-700 dark:text-gray-300">{{ $contact->phone_mobile ?? '-' }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">Office Phone</span>
                    <span class="text-gray-700 dark:text-gray-300">
                        {{ $contact->phone_office ?? '-' }} 
                        @if($contact->extension) (Ext: {{ $contact->extension }}) @endif
                    </span>
                </div>
                 <div class="flex flex-col">
                    <span class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">Linked User Account</span>
                     <span class="text-gray-700 dark:text-gray-300">{{ $contact->linked_user_account ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3 mt-6 pt-6 border-t dark:border-gray-700">
             <div class="flex items-center space-x-2">
                <span class="text-gray-700 dark:text-gray-300 font-semibold">VIP:</span>
                <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full {{ $contact->is_vip ? 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' : 'text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-100' }}">
                    {{ $contact->is_vip ? 'Yes' : 'No' }}
                </span>
            </div>
             <div class="flex items-center space-x-2">
                <span class="text-gray-700 dark:text-gray-300 font-semibold">Primary Contact:</span>
                <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full {{ $contact->is_primary ? 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' : 'text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-100' }}">
                    {{ $contact->is_primary ? 'Yes' : 'No' }}
                </span>
            </div>
             <div class="flex items-center space-x-2">
                <span class="text-gray-700 dark:text-gray-300 font-semibold">MFA Enforced:</span>
                <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full {{ $contact->mfa_enforced ? 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' : 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100' }}">
                    {{ $contact->mfa_enforced ? 'Yes' : 'No' }}
                </span>
            </div>
             <div class="flex items-center space-x-2">
                 <span class="text-gray-700 dark:text-gray-300 font-semibold">Emergency Contact:</span>
                <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full {{ $contact->emergency_contact_flag ? 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100' : 'text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-100' }}">
                    {{ $contact->emergency_contact_flag ? 'Yes' : 'No' }}
                </span>
            </div>
        </div>

        @if($contact->notes)
        <div class="mt-6 pt-6 border-t dark:border-gray-700">
            <h5 class="text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Notes</h5>
            <div class="mt-2 text-gray-700 dark:text-gray-300 prose dark:prose-invert">
                {{ $contact->notes }}
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
