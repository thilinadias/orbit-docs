<x-auth-split-layout>
    <x-slot name="title">Two-Factor Authentication</x-slot>
    <x-slot name="description">
        {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
    </x-slot>

    <form method="POST" action="{{ route('2fa.verify_challenge') }}" class="mt-8 space-y-6">
        @csrf

        <div>
            <x-input-label for="code" :value="__('Authentication Code')" class="text-gray-700 dark:text-gray-300 font-medium" />
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                     <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <x-text-input id="code" class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg leading-5 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm transition duration-150 ease-in-out" 
                    type="text" name="code" required autofocus autocomplete="one-time-code" placeholder="123456" />
            </div>
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                {{ __('Confirm') }}
                <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </button>
        </div>
    </form>
</x-auth-split-layout>
