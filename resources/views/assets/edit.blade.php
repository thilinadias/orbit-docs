<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <span>Edit Asset: {{ $asset->name }}</span>
            <a href="{{ route('assets.show', [$currentOrganization->slug, $asset->id]) }}" class="px-4 py-2 bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm font-medium hover:bg-gray-300 dark:hover:bg-gray-600">
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800"
         x-data="{ 
            selectedTypeId: '{{ old('asset_type_id', $asset->asset_type_id) }}', 
            types: {{ Js::from($types) }},
            values: {{ Js::from($asset->values->pluck('value', 'asset_custom_field_id')) }},
            get selectedType() { 
                return this.types.find(t => t.id == this.selectedTypeId); 
            },
            get fields() {
                return this.selectedType ? this.selectedType.fields : [];
            },
            getValue(fieldId) {
                return this.values[fieldId] || '';
            }
         }">
        <form action="{{ route('assets.update', [$currentOrganization->slug, $asset->id]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Name</span>
                    <input name="name" value="{{ $asset->name }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
            </div>

            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Asset Type</span>
                    <select name="asset_type_id" x-model="selectedTypeId" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option value="">Select an Asset Type</option>
                        <template x-for="type in types" :key="type.id">
                            <option :value="type.id" x-text="type.name" :selected="type.id == selectedTypeId"></option>
                        </template>
                    </select>
                </label>
            </div>

            <!-- Dynamic Fields -->
            <template x-if="fields.length > 0">
                <div class="p-4 mb-4 border rounded-lg dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
                        <span x-text="selectedType.name"></span> Details
                    </h4>
                    <div class="grid gap-6 md:grid-cols-2">
                        <template x-for="field in fields" :key="field.id">
                            <label class="block text-sm">
                                <span class="text-gray-700 dark:text-gray-400" x-text="field.name"></span>
                                
                                <!-- Text Input -->
                                <template x-if="field.field_type === 'text' || field.field_type === 'number'">
                                    <input :type="field.field_type" :name="`custom_fields[${field.id}]`" :value="getValue(field.id)" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                </template>

                                <!-- Date Input -->
                                <template x-if="field.field_type === 'date'">
                                    <input type="date" :name="`custom_fields[${field.id}]`" :value="getValue(field.id)" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                </template>

                                <!-- Select Input -->
                                <template x-if="field.field_type.startsWith('select:')">
                                    <select :name="`custom_fields[${field.id}]`" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                        <option value="">Select...</option>
                                        <template x-for="option in field.field_type.replace('select:', '').split(',')" :key="option">
                                            <option :value="option" x-text="option" :selected="option == getValue(field.id)"></option>
                                        </template>
                                    </select>
                                </template>
                            </label>
                        </template>
                    </div>
                </div>
            </template>

            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Site / Location</span>
                    <select name="site_id" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option value="">None</option>
                        @foreach($sites as $site)
                        <option value="{{ $site->id }}" {{ $asset->site_id == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="grid gap-6 mb-4 md:grid-cols-2">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Manufacturer</span>
                    <input name="manufacturer" value="{{ $asset->manufacturer }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Dell, HP, Apple" />
                </label>
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Model</span>
                    <input name="model" value="{{ $asset->model }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Latitude 7420" />
                </label>
            </div>

            <div class="grid gap-6 mb-4 md:grid-cols-2">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Serial Number</span>
                    <input name="serial_number" value="{{ $asset->serial_number }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Asset Tag</span>
                    <input name="asset_tag" value="{{ $asset->asset_tag }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="TAG-001" />
                </label>
            </div>

            <div class="grid gap-6 mb-4 md:grid-cols-2">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Assigned To</span>
                    <input name="assigned_to" value="{{ $asset->assigned_to }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="John Doe" />
                </label>
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Status</span>
                    <select name="status" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option value="active" {{ $asset->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="archived" {{ $asset->status == 'archived' ? 'selected' : '' }}>Archived</option>
                        <option value="broken" {{ $asset->status == 'broken' ? 'selected' : '' }}>Broken</option>
                    </select>
                </label>
            </div>

            <div class="grid gap-6 mb-4 md:grid-cols-3">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">IP Address</span>
                    <input name="ip_address" value="{{ $asset->ip_address }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="192.168.1.10" />
                </label>
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">MAC Address</span>
                    <input name="mac_address" value="{{ $asset->mac_address }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="00:11:22:33:44:55" />
                </label>
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">OS Version</span>
                    <input name="os_version" value="{{ $asset->os_version }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Windows 11 Pro" />
                </label>
            </div>

            <div class="grid gap-6 mb-4 md:grid-cols-3">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Purchase Date</span>
                    <input type="date" name="purchase_date" value="{{ $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '' }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Warranty Expiry</span>
                    <input type="date" name="warranty_expiration_date" value="{{ $asset->warranty_expiration_date ? $asset->warranty_expiration_date->format('Y-m-d') : '' }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">End of Life</span>
                    <input type="date" name="end_of_life" value="{{ $asset->end_of_life ? $asset->end_of_life->format('Y-m-d') : '' }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
            </div>

            <div class="flex gap-6 mb-4">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="monitoring_enabled" value="1" {{ $asset->monitoring_enabled ? 'checked' : '' }} class="form-checkbox text-purple-600 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" />
                    <span class="text-gray-700 dark:text-gray-400 text-sm">Monitoring Enabled</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="rmm_agent_installed" value="1" {{ $asset->rmm_agent_installed ? 'checked' : '' }} class="form-checkbox text-purple-600 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" />
                    <span class="text-gray-700 dark:text-gray-400 text-sm">RMM Agent Installed</span>
                </label>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">Notes</span>
                    <textarea name="notes" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3">{{ $asset->notes }}</textarea>
                </label>
            </div>

            <div class="mt-4">
                <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Update Asset
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
