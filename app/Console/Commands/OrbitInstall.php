<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrbitInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orbit:install {--force : Overwrite existing database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup OrbitDocs database and migrations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting OrbitDocs CLI Setup...');

        $dbHost = config('database.connections.mysql.host');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        $this->info("Target Database: {$dbName} on {$dbHost}");

        // Ensure DB exists
        try {
            $dsn = "mysql:host={$dbHost}";
            $pdo = new \PDO($dsn, $dbUser, $dbPass);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $this->info("Database '{$dbName}' ensured.");
        }
        catch (\Exception $e) {
            $this->warn("Database creation failed (might already exist): " . $e->getMessage());
        }

        // Run migrations
        $this->info('Running migrations and seeding (this may take a minute)...');
        $exitCode = Artisan::call('migrate:fresh', [
            '--force' => true,
            '--seed' => true,
        ], $this->getOutput());

        if ($exitCode === 0) {
            $this->info('Database setup completed successfully.');
            $this->info('You can now proceed to create your Admin account via the GUI or as next step.');
        }
        else {
            $this->error('Database setup failed. Check the output above.');
        }
    }
}
