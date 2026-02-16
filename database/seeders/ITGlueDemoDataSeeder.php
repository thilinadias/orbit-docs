<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\AssetCustomField;
use App\Models\AssetValue;
use App\Models\Site;
use App\Models\Credential;
use App\Models\Contact;

class ITGlueDemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Demo Organization exists
        $org = Organization::firstOrCreate(
            ['slug' => 'demo-msp'],
            ['name' => 'Demo MSP', 'legal_name' => 'Demo MSP Services LLC', 'primary_email' => 'support@demomsp.com', 'phone' => '555-0199', 'website' => 'https://demomsp.com']
        );

        // 2. Ensure a Site exists
        $site = Site::firstOrCreate(
            ['organization_id' => $org->id, 'name' => 'Headquarters'],
            ['address' => '123 Tech Blvd', 'city' => 'Silicon Valley', 'state' => 'CA', 'postcode' => '90210', 'site_manager' => 'John Doe', 'timezone' => 'PST', 'country' => 'USA']
        );

        // 3. Create Contacts (Enhanced)
        if (Contact::where('organization_id', $org->id)->count() == 0) {
            Contact::create([
                'organization_id' => $org->id,
                'first_name' => 'Alice',
                'last_name' => 'Smith',
                'title' => 'CEO',
                'department' => 'Executive',
                'email' => 'alice@demomsp.com',
                'phone_mobile' => '555-0100',
                'phone_office' => '555-0001',
                'extension' => '101',
                'location' => 'Headquarters',
                'is_vip' => true,
                'is_primary' => true,
                'mfa_enforced' => true,
                'access_level' => 'Full',
            ]);
            Contact::create([
                'organization_id' => $org->id,
                'first_name' => 'Bob',
                'last_name' => 'Jones',
                'title' => 'Office Manager',
                'department' => 'Operations',
                'email' => 'bob@demomsp.com',
                'phone_office' => '555-0101',
                'extension' => '102',
                'location' => 'Headquarters',
                'emergency_contact_flag' => true,
            ]);
        }

        // 4. Create Credentials (Enhanced)
        if (Credential::where('organization_id', $org->id)->count() == 0) {
            Credential::create([
                'organization_id' => $org->id,
                'title' => 'Domain Admin',
                'username' => 'admin_svc',
                'encrypted_password' => encrypt('P@ssw0rd123!'),
                'category' => 'AD',
                'auto_rotate' => true,
                'expiry_date' => now()->addDays(90),
                'access_log_enabled' => true,
                'owner' => 'IT Dept',
                'access_scope' => 'Global',
            ]);
        }

        // 5. Create Assets with Custom Fields (Exact Match)
        $demoAssets = [
            'MSP Information' => [
                [
                    'name' => 'MSP Info',
                    'fields' => [
                        'MSP Name' => 'Demo MSP',
                        'Legal Business Name' => 'Demo MSP Services LLC',
                        'Registration Number' => 'REG-99283',
                        'Tax ID / VAT' => 'US-99-2233',
                        'Primary Address' => '123 Tech Blvd, Silicon Valley',
                        'Country' => 'USA',
                        'Timezone' => 'PST',
                        'NOC Phone Number' => '800-555-0199',
                        'Emergency Support Line' => '800-555-9111',
                        'Main Email Address' => 'support@demomsp.com',
                        'Website' => 'https://demomsp.com',
                        'SLA Coverage Hours' => '8x5',
                        'Billing Cycle' => 'Monthly',
                        'PSA Tool Used' => 'ConnectWise Manage',
                        'RMM Tool Used' => 'Datto RMM',
                    ]
                ]
            ],
            'Primary MSP Information' => [
                [
                    'name' => 'Account Management',
                    'fields' => [
                        'Primary Account Manager' => 'Sarah Connor',
                        'Technical Account Manager' => 'John Reese',
                        'Primary Support Email' => 'help@demomsp.com',
                        'Tier 1 Contact' => 'Helpdesk Team',
                        'Escalation Contact' => 'Kyle Reese',
                        'Office Hours' => '9AM - 5PM PST',
                        'Emergency Process' => 'Call 911-IT',
                    ]
                ]
            ],
            'Client Support Scope' => [
                [
                    'name' => 'Support Contract',
                    'fields' => [
                        'Managed / Co-managed / Advisory' => 'Managed',
                        'Included Services' => 'Server, Workstation, Network Management',
                        'Excluded Services' => 'Printer Repair, Cabling',
                        'SLA Response Time' => '1 Hour',
                        'SLA Resolution Target' => '4 Hours',
                        'After Hours Coverage (Yes/No)' => 'Yes',
                        'Supported Locations' => 'Headquarters, Branch Office',
                        'Supported Devices Count' => 150,
                        'Supported Users Count' => 50,
                        'Backup Responsibility' => 'MSP',
                        'Security Monitoring Responsibility' => 'MSP',
                        'Patch Management Included' => 'Yes',
                        'Vendor Liaison Included' => 'Yes',
                    ]
                ]
            ],
            'Internet / WAN' => [
                [
                    'name' => 'Primary Fiber',
                    'fields' => [
                        'ISP Name' => 'Comcast Business',
                        'Circuit ID' => 'XF-9928-22',
                        'Static IP' => '50.2.3.4',
                        'Gateway' => '50.2.3.1',
                        'Subnet' => '255.255.255.248',
                        'Bandwidth' => '1000/1000 Mbps',
                        'SLA' => '99.9%',
                        'Failover Config' => 'LTE Backup',
                        'Router Model' => 'FortiGate 60F',
                        'Support Contact' => '800-391-3000',
                        'Notes' => 'DMARC is slightly bent in closet.',
                    ]
                ]
            ],
            'LAN' => [
                 [
                    'name' => 'Office LAN',
                    'fields' => [
                        'Subnet' => '192.168.10.0/24',
                        'VLAN List' => '1 (Data), 10 (Voice), 20 (Guest)',
                        'DHCP Server' => 'DC01',
                        'DNS Server' => '192.168.10.10',
                        'Gateway' => '192.168.10.1',
                        'Core Switch' => 'SW-CORE-01',
                        'IP Scheme' => '10-50 Servers, 100-200 DHCP',
                        'ACL Summary' => 'Block Guest to Corp',
                    ]
                ]
            ],
            'Active Directory' => [
                [
                    'name' => 'corp.demo.local',
                    'fields' => [
                        'Domain Name' => 'corp.demo.local',
                        'Forest Level' => 'Windows 2016',
                        'Domain Level' => 'Windows 2016',
                        'FSMO Role Owners' => 'DC01',
                        'Global Catalog Servers' => 'DC01, DC02',
                        'Azure AD Sync Enabled' => 'Yes',
                        'Time Source' => 'us.pool.ntp.org',
                        'Backup Enabled' => 'Yes',
                    ]
                ]
            ],
            'Applications' => [
                [
                    'name' => 'QuickBooks',
                    'fields' => [
                        'App Name' => 'QuickBooks Enterprise',
                        'Version' => '23.0',
                        'Vendor' => 'Intuit',
                        'Hosting Type (OnPrem/Cloud)' => 'OnPrem',
                        'Server Hosted On' => 'APP01',
                        'License Count' => 5,
                        'Support Contact' => 'Intuit Support',
                        'Criticality Level' => 'High',
                    ]
                ]
            ],
            'Backup' => [
                [
                    'name' => 'Datto backup',
                    'fields' => [
                        'Backup Solution' => 'Datto Siris',
                        'Backup Type' => 'Hybrid Cloud',
                        'Backup Frequency' => 'Hourly',
                        'Retention Policy' => '1 Year Cloud',
                        'Immutable Storage' => 'Yes',
                        'Encryption Enabled' => 'Yes',
                        'Alerting Enabled' => 'Yes',
                    ]
                ]
            ],
            'Email' => [
                [
                    'name' => 'M365 Tenant',
                    'fields' => [
                        'Platform (365 / GSuite / Exchange)' => 'Microsoft 365',
                        'Tenant ID' => '8345-2342-2342-2342',
                        'MX Records' => 'demomsp-com.mail.protection.outlook.com',
                        'DKIM Enabled' => 'Yes',
                        'DMARC Policy' => 'Quarantine',
                        'Mailbox Count' => 55,
                        'Security Policies' => 'MFA Enforced, Geo-Block Non-US',
                    ]
                ]
            ],
             'Licensing' => [
                [
                    'name' => 'Adobe CC',
                    'fields' => [
                        'Product' => 'Creative Cloud All Apps',
                        'Vendor' => 'Adobe',
                        'Number of Seats' => 10,
                        'Expiry Date' => '2025-05-20',
                        'Auto Renew' => 'Yes',
                    ]
                ]
            ],
            'Mobile Devices' => [
                 [
                    'name' => 'iPad Pro - CEO',
                    'manufacturer' => 'Apple',
                    'model' => 'iPad Pro 12.9',
                    'fields' => [
                        'Device Type' => 'Tablet',
                        'Model' => 'iPad Pro 12.9 5th Gen',
                        'Assigned User' => 'Alice Smith',
                        'MDM Enrolled' => 'Yes',
                        'OS Version' => 'iPadOS 17',
                        'SIM Provider' => 'Verizon',
                        'Status' => 'Active',
                    ]
                ]
            ],
            'Managed Printers' => [
                 [
                    'name' => 'Front Desk HP',
                    'manufacturer' => 'HP',
                    'model' => 'LaserJet Pro M404n',
                    'ip_address' => '192.168.10.50',
                    'fields' => [
                        'Model' => 'LaserJet Pro M404n',
                        'IP Address' => '192.168.10.50',
                        'Location' => 'Front Desk',
                        'Toner Type' => 'HP 58A',
                        'Monitoring Enabled' => 'Yes',
                    ]
                ]
            ],
            'Vendor Management' => [
                 [
                    'name' => 'Dell',
                    'fields' => [
                        'Vendor Name' => 'Dell Technologies',
                        'Vendor Type' => 'Hardware',
                        'Account Number' => '9938222',
                        'Support Number' => '800-456-3355',
                        'SLA' => 'ProSupport 4hr',
                    ]
                ]
            ],
            'Shared Drives' => [
                 [
                    'name' => 'Finance Share',
                    'fields' => [
                        'Drive Name' => 'S: Finance',
                        'Server' => 'FS01',
                        'Path' => '\\\\FS01\\Finance',
                        'Access Group' => 'GG_Finance_RW',
                        'Data Sensitivity' => 'High',
                    ]
                ]
            ],
            'Domains' => [
                 [
                    'name' => 'demomsp.com',
                    'fields' => [
                        'Domain Name' => 'demomsp.com',
                        'Registrar' => 'GoDaddy',
                        'Expiry Date' => '2028-01-15',
                        'Auto Renew' => 'Yes',
                        'Name Servers' => 'ns1.cloudflare.com',
                        'DNS Hosted At' => 'Cloudflare',
                        'WHOIS Privacy' => 'Yes',
                    ]
                ]
            ],
            'SSL Tracker' => [
                [
                    'name' => '*.demomsp.com',
                    'fields' => [
                        'Domain' => '*.demomsp.com',
                        'Issuer' => 'Digicert',
                        'Expiry Date' => '2025-12-31',
                        'Auto Renewal' => 'Yes',
                        'Certificate Type' => 'Wildcard',
                    ]
                ]
            ],
            'Router' => [
                [
                    'name' => 'Core Firewall',
                    'manufacturer' => 'Fortinet',
                    'model' => 'FortiGate 60F',
                    'ip_address' => '192.168.10.1',
                    'fields' => [
                        'Model' => 'FortiGate 60F',
                        'WAN IP' => '203.0.113.10',
                        'Firmware Version' => '7.2.5',
                        'VPN Enabled' => 'Yes',
                        'Backup Config Stored' => 'Yes',
                    ]
                ]
            ],
            'Wireless' => [
                [
                    'name' => 'Office WiFi',
                    'fields' => [
                        'Controller Model' => 'Ubiquiti Cloud Key',
                        'SSIDs' => 'Corp-Secure, Guest-WiFi',
                        'Security Type' => 'WPA2-Enterprise',
                        'Captive Portal' => 'No',
                        'VLAN Mapping' => 'Corp: 10, Guest: 20',
                    ]
                ]
            ],
            'Network Switch' => [
                [
                    'name' => 'Core Switch',
                    'manufacturer' => 'Cisco',
                    'model' => 'Catalyst 9300',
                    'ip_address' => '192.168.10.2',
                    'fields' => [
                        'Model' => 'Catalyst 9300',
                        'Firmware' => '17.3.4',
                        'Ports Count' => 48,
                        'PoE Enabled' => 'Yes',
                        'Stacking Config' => 'Master of 2',
                    ]
                ]
            ],
            'Servers - Windows' => [
                [
                    'name' => 'DC01',
                    'manufacturer' => 'Dell',
                    'model' => 'PowerEdge R640',
                    'ip_address' => '192.168.10.10',
                    'os_version' => 'Windows Server 2022',
                    'fields' => [
                        'Server Name' => 'DC01',
                        'Role (DC/File/App)' => 'DC',
                        'OS Version' => 'Windows Server 2022',
                        'IP Address' => '192.168.10.10',
                        'CPU' => '4 vCPU',
                        'RAM' => '16 GB',
                        'Backup Enabled' => 'Yes',
                    ]
                ]
            ],
            'Servers - ESXi' => [
                [
                    'name' => 'HOST01',
                    'manufacturer' => 'Dell',
                    'ip_address' => '192.168.10.5',
                    'fields' => [
                        'ESXi Version' => '7.0 U3',
                        'Hostname' => 'HOST01',
                        'vCenter' => 'vcenter.demo.local',
                        'CPU' => '2x Gold 6248',
                        'RAM' => '512 GB',
                        'Datastores' => 'DS_01, DS_02',
                    ]
                ]
            ],
        ];

        foreach ($demoAssets as $typeName => $assetsData) {
            $type = AssetType::where('name', $typeName)->first();
            if (!$type) continue;

            foreach ($assetsData as $data) {
                // Update or Create Asset
                $coreData = [
                    'organization_id' => $org->id,
                    'asset_type_id' => $type->id,
                    'site_id' => $site->id,
                    'name' => $data['name'],
                    // Core fields if provided
                    'manufacturer' => $data['manufacturer'] ?? null,
                    'model' => $data['model'] ?? null,
                    'ip_address' => $data['ip_address'] ?? null,
                    'os_version' => $data['os_version'] ?? null,
                    'monitoring_enabled' => $data['monitoring_enabled'] ?? false,
                ];

                $asset = Asset::firstOrCreate(
                    ['organization_id' => $org->id, 'name' => $data['name']],
                    $coreData
                );
                $asset->update($coreData);

                // Handle custom fields
                if (isset($data['fields'])) {
                    foreach ($data['fields'] as $fieldName => $value) {
                        $field = AssetCustomField::where('asset_type_id', $type->id)
                                               ->where('name', $fieldName)
                                               ->first();
                        
                        // Only try to set if the field exists (it should if seeder ran)
                        if ($field) {
                            AssetValue::updateOrCreate(
                                [
                                    'asset_id' => $asset->id,
                                    'asset_custom_field_id' => $field->id,
                                ],
                                ['value' => $value]
                            );
                        }
                    }
                }
            }
        }
    }
}
