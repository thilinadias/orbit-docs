<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Setup Two-Factor Authentication') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Scan QR Code') }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('To enable Two-Factor Authentication, scan the following QR code using your authenticator application (e.g., Google Authenticator, Authy).') }}
                    </p>

                    <div class="mt-4 p-4 bg-white inline-block rounded-lg">
                        {!! $qrCodeImage !!}
                    </div>

                    <div class="mt-4">
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ __('Setup Key') }}: <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $secret }}</span>
                        </p>
                    </div>

                    <form method="POST" action="{{ route('2fa.enable') }}" class="mt-6 space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="code" :value="__('Authentication Code')" />
                            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" autofocus autocomplete="one-time-code" placeholder="123456" />
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Enable 2FA') }}</x-primary-button>
                            @if(!Auth::user()->is_2fa_enforced)
                                <a href="{{ route('profile.edit') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                    {{ __('Cancel') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
