<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearEmailCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-email-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear daily email cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();
        $userIds = Cache::get("email_sent_users_$today", []);

        foreach ($userIds as $userId) {
            $key = "email_sent_{$userId}_$today";
            Cache::forget($key);
            $this->info("Cleared cache: $key");
        }

        Cache::forget("email_sent_users_$today");
        $this->info("All email caches for $today cleared.");
    }
}
