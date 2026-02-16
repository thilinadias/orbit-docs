<x-installer-layout>
    <div class="space-y-6">
        <div>
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">System Check</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Verifying your server environment.</p>
        </div>

        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($requirements as $label => $pass)
                <li class="flex py-4">
                    <div class="ml-3 flex flex-grow flex-col">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $label }}</span>
                    </div>
                    <div>
                        @if($pass)
                             <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                OK
                            </span>
                        @else
                             <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                FAIL
                            </span>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="flex justify-end">
            @if($allMet)
                <a href="{{ route('install.database') }}" class="flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Next: Database Configuration
                </a>
            @else
                <button disabled class="flex w-full justify-center rounded-md border border-transparent bg-gray-400 py-2 px-4 text-sm font-medium text-white shadow-sm cursor-not-allowed">
                    Please fix requirements to proceed
                </button>
            @endif
        </div>
    </div>
</x-installer-layout>
