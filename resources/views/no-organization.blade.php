<x-app-layout :hideSidebar="true">
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Welcome to OrbitDocs') }}
        </h2>
    </x-slot>

    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
        <div class="p-8 bg-white rounded-lg shadow-md dark:bg-gray-800 max-w-md w-full">
            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">No Organization Access</h3>
            
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                You are currently not a member of any organization. Please contact your system administrator to be invited or assigned to an organization.
            </p>

            <div class="mt-6 text-2xl font-bold text-gray-500">
             DEBUG INFO: User ID: {{ auth()->id() }} | Super Admin: {{ auth()->user()->is_super_admin ? 'YES' : 'NO' }}
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit" class="w-full px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
