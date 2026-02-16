<x-app-layout :hideSidebar="true" :topNav="true">
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('globalDashboard', () => ({
                favorites: @json($favoritesIds),
                
                isFavorite(id, type) {
                    return this.favorites.includes(id);
                },

                async toggleFavorite(id, type) {
                    try {
                        const response = await fetch('{{ route('api.favorites.toggle') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                favoritable_id: id,
                                favoritable_type: type
                            })
                        });
                        
                        const data = await response.json();
                        if (data.status === 'added') {
                            this.favorites.push(id);
                        } else {
                            this.favorites = this.favorites.filter(fid => fid !== id);
                        }
                    } catch (error) {
                        console.error('Failed to toggle favorite:', error);
                    }
                }
            }))
        })
    </script>

    <div x-data="globalDashboard">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            
            <!-- Left Column (3/4 width) -->
            <div class="md:col-span-3 space-y-6">
                
                <!-- Favorites Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center mb-6">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.783.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Favorites</h3>
                    </div>
                    
                    <div class="flex flex-wrap gap-8">
                        @forelse($favorites as $fav)
                        <a href="{{ $fav->url }}" class="group flex flex-col items-center w-24">
                            <div class="relative w-16 h-16 mb-2 flex items-center justify-center bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-700 group-hover:border-purple-500 transition-all duration-200 shadow-sm overflow-hidden">
                                @if($fav->logo)
                                    <img src="{{ asset('storage/' . $fav->logo) }}" alt="{{ $fav->name }}" class="w-10 h-10 object-contain group-hover:scale-110 transition-transform">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-purple-50 dark:bg-purple-900 border-none">
                                        <span class="text-xl font-bold text-purple-600 dark:text-purple-300 group-hover:scale-110 transition-transform">{{ substr(str_replace(['[', ']'], '', $fav->name), 0, 1) }}{{ substr(str_replace(['[', ']'], '', $fav->name), -1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 text-center truncate w-full group-hover:text-purple-600 leading-tight">{{ $fav->name }}</span>
                        </a>
                        @empty
                            <p class="text-[10px] text-gray-400 italic">No favorites defined yet. Star an MSP below!</p>
                        @endforelse
                    </div>
                </div>

                <!-- System Usage Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                            </svg>
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">System Usage (Historical)</h3>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-4 mb-8 text-center px-4">
                        <div class="flex-1 min-w-[100px] border-r border-gray-100 dark:border-gray-700 last:border-0 border-opacity-50">
                            <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($total_assets) }}</p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase mt-1 tracking-widest">Assets</p>
                        </div>
                        <div class="flex-1 min-w-[100px] border-r border-gray-100 dark:border-gray-700 last:border-0 border-opacity-50">
                            <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($total_documents) }}</p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase mt-1 tracking-widest">Documents</p>
                        </div>
                        <div class="flex-1 min-w-[100px] border-r border-gray-100 dark:border-gray-700 last:border-0 border-opacity-50">
                            <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($total_contacts) }}</p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase mt-1 tracking-widest">Contacts</p>
                        </div>
                        <div class="flex-1 min-w-[100px] border-r border-gray-100 dark:border-gray-700 last:border-0 border-opacity-50">
                            <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($total_organizations) }}</p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase mt-1 tracking-widest">Orgs</p>
                        </div>
                        <div class="flex-1 min-w-[100px]">
                            <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ number_format($total_credentials) }}</p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase mt-1 tracking-widest">Credentials</p>
                        </div>
                    </div>

                    <div class="h-80 w-full">
                        <canvas id="usageChart"></canvas>
                    </div>
                </div>

                <!-- Organization Explorer -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Organization Explorer</h3>
                        <a href="{{ route('organizations.index') }}" class="text-xs text-purple-600 hover:underline">Manage All</a>
                    </div>
                    <div class="w-full overflow-x-auto">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-400 uppercase bg-gray-50 dark:bg-gray-900 border-b dark:border-gray-700">
                                    <th class="px-6 py-4">Organization</th>
                                    <th class="px-6 py-4">Metrics</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y dark:divide-gray-700">
                                @foreach($organizations->take(8) as $org)
                                <tr class="text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <button 
                                                @click="toggleFavorite({{ $org->id }}, 'App\\Models\\Organization')"
                                                class="mr-4 focus:outline-none group"
                                            >
                                                <svg class="w-5 h-5 transition-colors" :class="isFavorite({{ $org->id }}, 'App\\Models\\Organization') ? 'text-yellow-400 fill-current' : 'text-gray-300 dark:text-gray-600 group-hover:text-yellow-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.783.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                </svg>
                                            </button>
                                            <div class="w-8 h-8 mr-3 rounded-lg bg-gray-100 dark:bg-gray-700 p-1 flex items-center justify-center">
                                                @if($org->logo)
                                                    <img src="{{ asset('storage/' . $org->logo) }}" class="w-full h-full object-contain">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center font-bold text-[10px] uppercase text-purple-600">
                                                        {{ substr($org->name, 0, 2) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-bold text-sm">
                                                    @if($org->parent)
                                                        <span class="text-gray-400 font-normal">[{{ $org->parent->name }}]</span>
                                                    @endif
                                                    {{ $org->name }}
                                                </p>
                                                <p class="text-xs text-gray-400">{{ $org->slug }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-bold text-gray-700 dark:text-gray-200">{{ $org->assets_count }}</span>
                                                <span class="text-[9px] uppercase text-gray-400">Assets</span>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-xs font-bold text-gray-700 dark:text-gray-200">{{ $org->documents_count }}</span>
                                                <span class="text-[9px] uppercase text-gray-400">Docs</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($org->isSuspended())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 uppercase">
                                                <span class="w-1.5 h-1.5 mr-1.5 bg-yellow-500 rounded-full"></span> Suspended
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 uppercase">
                                                <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span> Active
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('dashboard', $org->slug) }}" class="bg-gray-100 dark:bg-gray-700 hover:bg-purple-600 hover:text-white dark:hover:bg-purple-600 text-gray-600 dark:text-gray-200 text-[10px] font-bold py-1.5 px-4 rounded-lg transition-all">View Dashboard</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column (1/4 width) -->
            <div class="space-y-6">
                
                <!-- Popular This Week -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <div class="flex items-center mb-6">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Popular This Week</h3>
                    </div>
                    
                    <div class="relative pl-6 space-y-8">
                        <div class="absolute left-1.5 top-0 h-full w-0.5 bg-gray-100 dark:bg-gray-700"></div>
                        
                        @foreach($popular_items as $item)
                        <div class="relative">
                            <div class="absolute -left-6 top-1.5 w-3 h-3 rounded-full bg-white dark:bg-gray-800 border-2 border-purple-500"></div>
                            <a href="{{ route('dashboard', $item->slug) }}" class="block p-3 bg-gray-50 dark:bg-gray-900 rounded-lg group cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <p class="text-xs font-bold text-gray-700 dark:text-gray-200 group-hover:text-purple-500 transition-colors">{{ $item->name }}</p>
                                <p class="text-[10px] text-gray-500 uppercase font-bold mt-1 text-opacity-70">{{ $item->activity_logs_count }} Activities This Week</p>
                                <p class="text-[9px] text-gray-400 mt-1 italic">Last activity {{ $item->activityLogs()->latest()->first()?->created_at->diffForHumans() ?? 'N/A' }}</p>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recently Viewed -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <div class="flex items-center mb-6">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Recently Viewed By You</h3>
                    </div>
                    
                    <ul class="space-y-4">
                        @forelse($recent_activity->take(5) as $activity)
                        <li class="flex items-start group cursor-pointer">
                            <a href="{{ route('dashboard', $activity->organization->slug) }}" class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-purple-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300 group-hover:text-purple-600 transition-colors">{{ $activity->organization->name }}</p>
                                    <p class="text-[9px] text-gray-400 italic capitalise">{{ $activity->action }} • {{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                        </li>
                        @empty
                        <p class="text-[10px] text-gray-400 italic text-center">No recent activity found.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('usageChart').getContext('2d');
        const usageChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chart_data['labels']) !!},
                datasets: [
                    {
                        label: 'Assets',
                        data: {!! json_encode($chart_data['assets']) !!},
                        fill: true,
                        backgroundColor: 'rgba(234, 88, 12, 0.05)',
                        borderColor: 'rgba(234, 88, 12, 0.8)',
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(234, 88, 12, 1)',
                        tension: 0.4
                    },
                    {
                        label: 'Documents',
                        data: {!! json_encode($chart_data['documents']) !!},
                        fill: true,
                        backgroundColor: 'rgba(37, 99, 235, 0.05)',
                        borderColor: 'rgba(37, 99, 235, 0.8)',
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(37, 99, 235, 1)',
                        tension: 0.4
                    },
                    {
                        label: 'Contacts',
                        data: {!! json_encode($chart_data['contacts']) !!},
                        fill: true,
                        backgroundColor: 'rgba(147, 51, 234, 0.05)',
                        borderColor: 'rgba(147, 51, 234, 0.8)',
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(147, 51, 234, 1)',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            font: { size: 10, weight: '600' },
                            padding: 20
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(156, 163, 175, 0.05)'
                        },
                        ticks: {
                            font: { size: 10, weight: '500' }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { size: 10, weight: '500' }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
