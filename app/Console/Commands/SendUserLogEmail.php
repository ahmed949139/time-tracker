<?php

namespace App\Console\Commands;

use App\Mail\UserLogMail;
use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SendUserLogEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-user-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to users who logged 8+ hours today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();

        $logs = TimeLog::whereDate('start_time', $today)->get();

        $userLogs = [];

        foreach ($logs as $log) {
            $userId = optional($log->project->client)->contact_person;

            if ($userId) {
                $hours = now()->diffInMinutes($log->start_time) / 60;

                if ($hours >= 8) {
                    if (!isset($userLogs[$userId])) {
                        $userLogs[$userId] = 0;
                    }
                    $userLogs[$userId] += $hours;
                }
            }
        }
        
        foreach ($userLogs as $userId => $totalHours) {
            if ($totalHours >= 8) {
                $user = User::find($userId);
                if (!$user) continue;

                $cacheKey = "email_sent_{$userId}_$today";
                if (!Cache::get($cacheKey)) {
                    Mail::to($user->email)->send(new UserLogMail($user, $totalHours));
                    Cache::put($cacheKey, true, now()->endOfDay());
                    $this->info("Email sent to user ID: $userId");
                }
            }
        }

        Cache::put("email_sent_{$userId}_$today", true, now()->endOfDay());

        $userListKey = "email_sent_users_$today";
        $userIds = Cache::get($userListKey, []);
        if (!in_array($userId, $userIds)) {
            $userIds[] = $userId;
            Cache::put($userListKey, $userIds, now()->endOfDay());
        }        

        $this->info("Checked all logs for today.");
    }
}
