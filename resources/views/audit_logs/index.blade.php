<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Audit Logs') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        isModalOpen: false, 
        selectedLog: null,
        formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateString).toLocaleDateString(undefined, options);
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Filters --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                        <form method="GET" action="{{ route('audit-logs.index') }}" class="flex w-full md:w-auto space-x-2">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search logs..." class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            
                            <select name="module" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">All Modules</option>
                                @foreach($modules as $module)
                                    <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                                        {{ ucfirst($module) }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Filter
                            </button>
                            
                            @if(request()->anyFilled(['search', 'module']))
                                <a href="{{ route('audit-logs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Clear
                                </a>
                            @endif

                        </form>

                        <div class="flex items-center ml-2">
                            <a href="{{ route('audit-logs.export', request()->query()) }}" style="background-color: #16a34a; color: white;" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Export CSV
                            </a>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-3 px-6">User</th>
                                    <th scope="col" class="py-3 px-6">Action</th>
                                    <th scope="col" class="py-3 px-6">Module</th>
                                    <th scope="col" class="py-3 px-6">Subject</th>
                                    <th scope="col" class="py-3 px-6">Description</th>
                                    <th scope="col" class="py-3 px-6">Date</th>
                                    <th scope="col" class="py-3 px-6 text-right">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $log)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            @if($log->user)
                                                <div class="flex items-center">
                                                    {{-- Avatar Placeholder --}}
                                                    <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold mr-2 text-xs">
                                                        {{ substr($log->user->name, 0, 1) }}
                                                    </div>
                                                    <span>{{ $log->user->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic">System / Deleted User</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6">
                                        <td class="py-4 px-6">
                                            @php
                                                $badgeClass = match($log->action) {
                                                    'created', 'login', 'activated' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                    'updated' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                                    'deleted', 'suspended' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                                    'restored' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                                    'logout' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                                };
                                            @endphp
                                            <span class="{{ $badgeClass }} text-xs font-medium mr-2 px-2.5 py-0.5 rounded">
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                                                {{ ucfirst($log->log_name) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                           @if($log->subject)
                                                {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                           @else
                                                {{ class_basename($log->subject_type) }} #{{ $log->subject_id }} <span class="text-xs text-red-500">(Deleted)</span>
                                           @endif
                                        </td>
                                         <td class="py-4 px-6">
                                            {{ $log->description }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $log->created_at->diffForHumans() }}
                                            <div class="text-xs text-gray-400">{{ $log->created_at->format('M d, Y H:i:s') }}</div>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <button 
                                                @click="selectedLog = {{ json_encode($log->only(['id', 'description', 'action', 'log_name', 'created_at', 'old_values', 'new_values', 'ip_address'])) }}; isModalOpen = true"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline" 
                                                title="View Changes">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="7" class="py-4 px-6 text-center text-gray-500 dark:text-gray-400">
                                            No audit logs found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>

                </div>
            </div>
        </div>

        {{-- Details Modal --}}
        <div x-show="isModalOpen" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                
                {{-- Backdrop --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="isModalOpen = false" aria-hidden="true"></div>

                {{-- Modal Panel --}}
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                    Audit Log Details
                                </h3>
                                <div class="mt-4">
                                    <template x-if="selectedLog">
                                        <div class="space-y-4 text-sm text-gray-500 dark:text-gray-400">
                                            <div>
                                                <span class="font-bold">Event:</span> <span x-text="selectedLog.action.toUpperCase()"></span>
                                            </div>
                                            <div>
                                                <span class="font-bold">Description:</span> <span x-text="selectedLog.description"></span>
                                            </div>
                                            <div>
                                                <span class="font-bold">Time:</span> <span x-text="formatDate(selectedLog.created_at)"></span>
                                            </div>
                                            <div>
                                                <span class="font-bold">IP Address:</span> <span x-text="selectedLog.ip_address || 'N/A'"></span>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4 mt-4">
                                                {{-- Old Values --}}
                                                <div>
                                                    <h4 class="font-bold mb-2 text-gray-700 dark:text-gray-300">Old Values</h4>
                                                    <div class="bg-gray-100 dark:bg-gray-900 p-2 rounded overflow-auto max-h-60">
                                                        <template x-if="selectedLog.old_values && Object.keys(selectedLog.old_values).length > 0">
                                                            <pre class="text-xs" x-text="JSON.stringify(selectedLog.old_values, null, 2)"></pre>
                                                        </template>
                                                        <template x-if="!selectedLog.old_values || Object.keys(selectedLog.old_values).length === 0">
                                                            <span class="italic text-gray-400">None</span>
                                                        </template>
                                                    </div>
                                                </div>

                                                {{-- New Values --}}
                                                <div>
                                                    <h4 class="font-bold mb-2 text-gray-700 dark:text-gray-300">New Values</h4>
                                                    <div class="bg-gray-100 dark:bg-gray-900 p-2 rounded overflow-auto max-h-60">
                                                        <template x-if="selectedLog.new_values && Object.keys(selectedLog.new_values).length > 0">
                                                            <pre class="text-xs" x-text="JSON.stringify(selectedLog.new_values, null, 2)"></pre>
                                                        </template>
                                                         <template x-if="!selectedLog.new_values || Object.keys(selectedLog.new_values).length === 0">
                                                            <span class="italic text-gray-400">None</span>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm" @click="isModalOpen = false">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
