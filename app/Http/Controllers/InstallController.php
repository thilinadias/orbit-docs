<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InstallController extends Controller
{
    public function welcome()
    {
        $requirements = [
            'PHP Version >= 8.1.0' => phpversion() >= '8.1.0',
            'BCMath Extension' => extension_loaded('bcmath'),
            'Ctype Extension' => extension_loaded('ctype'),
            'JSON Extension' => extension_loaded('json'),
            'Mbstring Extension' => extension_loaded('mbstring'),
            'OpenSSL Extension' => extension_loaded('openssl'),
            'PDO Extension' => extension_loaded('pdo'),
            'Tokenizer Extension' => extension_loaded('tokenizer'),
            'XML Extension' => extension_loaded('xml'),
            '.env Writable' => is_writable(base_path('.env')) || is_writable(base_path()),
            'storage Writable' => is_writable(storage_path()),
            'bootstrap/cache Writable' => is_writable(base_path('bootstrap/cache')),
        ];

        $allMet = !in_array(false, $requirements);

        return view('install.welcome', compact('requirements', 'allMet'));
    }

    public function database()
    {
        return view('install.database');
    }

    public function storeDatabase(Request $request)
    {
        $request->validate([
            'db_host' => 'required',
            'db_port' => 'required',
            'db_database' => 'required',
            'db_username' => 'required',
            'db_password' => 'nullable',
        ]);

        try {
            $dsn = "mysql:host={$request->db_host};port={$request->db_port}";
            $pdo = new \PDO($dsn, $request->db_username, $request->db_password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$request->db_database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        } catch (\Exception $e) {
            // Ignore
        }

        $this->updateEnvironmentFile([
            'DB_HOST' => $request->db_host,
            'DB_PORT' => $request->db_port,
            'DB_DATABASE' => $request->db_database,
            'DB_USERNAME' => $request->db_username,
            'DB_PASSWORD' => $request->db_password ?? '',
        ]);

        Artisan::call('config:clear');

        try {
            config([
                'database.connections.mysql.host' => $request->db_host,
                'database.connections.mysql.port' => $request->db_port,
                'database.connections.mysql.database' => $request->db_database,
                'database.connections.mysql.username' => $request->db_username,
                'database.connections.mysql.password' => $request->db_password,
            ]);
            DB::reconnect('mysql');
            DB::connection()->getPdo();
        }
        catch (\Exception $e) {
            return back()->with('error', 'Could not connect: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('install.migrations');
    }

    public function migrations()
    {
        return view('install.migrations');
    }

    public function runMigrations()
    {
        $progressPath = storage_path('app/install_progress.json');
        
        file_put_contents($progressPath, json_encode([
            'progress' => 10,
            'step' => 'Wiping database and starting migrations...',
            'status' => 'running'
        ]));

        try {
            \Illuminate\Support\Facades\Auth::logout();
            \Illuminate\Support\Facades\Session::flush();
            set_time_limit(300);

            file_put_contents($progressPath, json_encode(['progress' => 40, 'step' => 'Running migrations (this may take a minute)...', 'status' => 'running']));
            Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);

            file_put_contents($progressPath, json_encode(['progress' => 100, 'step' => 'Setup Complete!', 'status' => 'done']));
            
            return response()->json(['success' => true]);
        }
        catch (\Exception $e) {
            file_put_contents($progressPath, json_encode([
                'progress' => 50,
                'step' => 'Error: ' . $e->getMessage(),
                'status' => 'error',
                'message' => $e->getMessage()
            ]));
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function migrationStatus()
    {
        $path = storage_path('app/install_progress.json');
        if (file_exists($path)) {
            return response()->json(json_decode(file_get_contents($path)));
        }
        return response()->json(['progress' => 5, 'step' => 'Initializing...', 'status' => 'running']);
    }

    public function admin()
    {
        return view('install.admin');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_super_admin' => true,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('install.organization');
    }

    public function organization()
    {
        return view('install.organization');
    }

    public function storeOrganization(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:organizations,slug',
        ]);

        $organization = \App\Models\Organization::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
        ]);

        $user = User::first();
        if ($user) {
            $adminRole = Role::where('name', 'Admin')->first();
            $user->organizations()->attach($organization->id, ['role_id' => $adminRole?->id]);
        }

        return redirect()->route('install.network');
    }

    public function network()
    {
        return view('install.network');
    }

    public function finish(Request $request)
    {
        file_put_contents(storage_path('app/installed'), 'INSTALLED ON ' . now());
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        return redirect()->route('login')->with('status', 'Installation Completed!');
    }

    protected function updateEnvironmentFile($data)
    {
        $path = base_path('.env');
        if (!file_exists($path) && file_exists(base_path('.env.example'))) {
            copy(base_path('.env.example'), $path);
        }

        if (file_exists($path)) {
            $currentEnv = file_get_contents($path);
            foreach ($data as $key => $value) {
                if (str_contains($value, ' ') && !str_starts_with($value, '"')) {
                    $value = "\"{$value}\"";
                }
                if (preg_match("/^{$key}=/m", $currentEnv)) {
                    $currentEnv = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $currentEnv);
                } else {
                    $currentEnv .= "\n{$key}={$value}";
                }
            }
            file_put_contents($path, $currentEnv);
        }
    }
}
