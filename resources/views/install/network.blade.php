<x-installer-layout>
    <form action="{{ route('install.finish') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ type: 'ip' }">
        @csrf
        <div>
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Network Configuration</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure how users will access OrbitDocs.</p>
        </div>

        <div>
            <label class="text-base font-medium text-gray-900 dark:text-white">Access Method</label>
            <fieldset class="mt-4">
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input id="type_ip" name="network_type" type="radio" value="ip" x-model="type" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="type_ip" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            IP Address (Auto-detect)
                        </label>
                    </div>
                     <p class="ml-7 text-xs text-gray-500">
                         This server appears to be: <strong>{{ request()->getHost() }}</strong>
                     </p>

                    <div class="flex items-center">
                        <input id="type_domain" name="network_type" type="radio" value="domain" x-model="type" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="type_domain" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Custom Domain Name
                        </label>
                    </div>
                </div>
            </fieldset>
        </div>

        <div x-show="type === 'domain'" class="space-y-6" x-cloak>
            <div>
                <label for="domain" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Domain Name</label>
                <div class="mt-1">
                    <input type="text" name="domain" id="domain" placeholder="docs.example.com" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                </div>
            </div>

            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">DNS Configuration</h3>
                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                            <p>Ensure your domain's A Record points to this server's IP address ({{ request()->getHost() }}) before proceeding.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-900 dark:text-white">SSL Certificate (Optional)</h4>
                <p class="text-xs text-gray-500 mb-4">If skipped, the system will use HTTP or a self-signed certificate.</p>
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="ssl_cert" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Certificate (.crt/.pem)</label>
                        <div class="mt-1">
                            <input type="file" name="ssl_cert" id="ssl_cert" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="ssl_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Private Key (.key)</label>
                        <div class="mt-1">
                            <input type="file" name="ssl_key" id="ssl_key" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="flex w-full justify-center rounded-md border border-transparent bg-green-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
            Finish & Launch
        </button>
    </form>
</x-installer-layout>
