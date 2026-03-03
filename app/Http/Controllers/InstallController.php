<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

    public function requirements()
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
            '.env Writable' => is_writable(base_path('.env')),
            'storage Writable' => is_writable(storage_path()),
            'bootstrap/cache Writable' => is_writable(base_path('bootstrap/cache')),
        ];

        return view('install.requirements', compact('requirements'));
    }

    public function database()
    {
        return view('install.database');
    }

    public function status()
    {
        $path = storage_path('app/install_progress.json');
        if (file_exists($path)) {
            return response()->json(json_decode(file_get_contents($path)));
        }
        return response()->json(['progress' => 0, 'status' => 'Starting...']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'db_host' => 'required',
            'db_port' => 'required',
            'db_database' => 'required',
            'db_username' => 'required',
            'admin_name' => 'required',
            'admin_email' => 'required|email',
            'admin_password' => 'required|min:8',
        ]);

        try {
            $progressPath = storage_path('app/install_progress.json');
            file_put_contents($progressPath, json_encode(['progress' => 10, 'status' => 'Updating environment...']));

            // Before testing the connection, we ensure the database exists.
            try {
                $dsn = "mysql:host={$request->db_host};port={$request->db_port}";
                $pdo = new \PDO($dsn, $request->db_username, $request->db_password);
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$request->db_database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            }
            catch (\Exception $e) {
            // Ignore failure here; migrate:fresh will fail clearly if DB is missing
            }

            // Update .env
            $this->updateEnvironmentFile([
                'DB_HOST' => $request->db_host,
                'DB_PORT' => $request->db_port,
                'DB_DATABASE' => $request->db_database,
                'DB_USERNAME' => $request->db_username,
                'DB_PASSWORD' => $request->db_password ?? '',
            ]);

            file_put_contents($progressPath, json_encode(['progress' => 30, 'status' => 'Clearing configuration...']));
            // Clear config cache
            Artisan::call('config:clear');

            file_put_contents($progressPath, json_encode(['progress' => 50, 'status' => 'Running migrations... This might take a moment.']));

            // Increase time limit for migrations
            set_time_limit(300);

            // Run migrations
            Artisan::call('migrate:fresh', ['--force' => true]);

            file_put_contents($progressPath, json_encode(['progress' => 80, 'status' => 'Creating administrator...']));
            // Create admin user
            User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'is_super_admin' => true,
            ]);

            file_put_contents($progressPath, json_encode(['progress' => 100, 'status' => 'Complete!']));

            // Mark as installed
            file_put_contents(storage_path('app/installed'), 'INSTALLED ON ' . now());
            @unlink($progressPath);

            return redirect()->route('login')->with('status', 'Installation Completed Successfully!');
        }
        catch (\Exception $e) {
            @unlink($progressPath);
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
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
                // Ensure the value is quoted if it contains spaces or special characters
                if (str_contains($value, ' ') && !str_starts_with($value, '"')) {
                    $value = "\"{$value}\"";
                }

                // Check if key exists
                if (preg_match("/^{$key}=/m", $currentEnv)) {
                    $currentEnv = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $currentEnv);
                }
                else {
                    // Append if not exists
                    $currentEnv .= "\n{$key}={$value}";
                }
            }

            file_put_contents($path, $currentEnv);
        }
    }
}
