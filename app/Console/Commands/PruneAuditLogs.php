<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActivityLog;
use Carbon\Carbon;

class PruneAuditLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:prune {--days=90 : The number of days to retain logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune audit logs older than a specified number of days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $date = Carbon::now()->subDays($days);

        $count = ActivityLog::where('created_at', '<', $date)->count();

        if ($count > 0) {
            ActivityLog::where('created_at', '<', $date)->delete();
            $this->info("Successfully deleted {$count} audit log entries older than {$days} days.");
        } else {
            $this->info("No audit logs found older than {$days} days.");
        }
    }
}
