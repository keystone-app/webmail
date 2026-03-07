<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\ImapSyncJob;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SyncSchedulerService
{
    public const SYNC_INTERVAL = 300; // 5 minutes
    public const SYNC_WINDOW = 30; // 30 seconds
    public const DEBOUNCE_TIME = 5; // 5 seconds

    /**
     * Schedule initial sync on login.
     *
     * @param User $user
     * @param string $password
     * @return void
     */
    public function scheduleInitialSync(User $user, string $password): void
    {
        $this->scheduleSync($user, $password, 0, 'INBOX', true);
    }

    /**
     * Schedule a sync job.
     *
     * @param User $user
     * @param string $password
     * @param int $delay Seconds
     * @param string $folder
     * @param bool $isFull
     * @return void
     */
    public function scheduleSync(User $user, string $password, int $delay = 0, string $folder = 'INBOX', bool $isFull = false): void
    {
        $syncInstanceId = Str::uuid()->toString();
        $nextSyncTime = time() + $delay;

        Cache::put("next_sync_job_id_{$user->id}", $syncInstanceId, self::SYNC_INTERVAL + 60);
        Cache::put("next_sync_time_{$user->id}", $nextSyncTime, self::SYNC_INTERVAL + 60);

        $job = (new ImapSyncJob($user, $password, $folder, $syncInstanceId))
            ->delay($delay);
        
        dispatch($job);
    }

    /**
     * Cancel future sync jobs for a user if they are more than 30s away.
     *
     * @param User $user
     * @return bool Whether the cancellation happened.
     */
    public function cancelFutureSyncs(User $user): bool
    {
        $nextSyncTime = (int) Cache::get("next_sync_time_{$user->id}");
        $nextSyncJobId = Cache::get("next_sync_job_id_{$user->id}");

        if (!$nextSyncJobId) {
            return false;
        }

        // Only cancel if sync is scheduled to run in more than 30 seconds
        if ($nextSyncTime > time() + self::SYNC_WINDOW) {
            Cache::put("cancelled_job_{$nextSyncJobId}", true, self::SYNC_INTERVAL + 60);
            Cache::forget("next_sync_job_id_{$user->id}");
            Cache::forget("next_sync_time_{$user->id}");
            return true;
        }

        return false;
    }

    /**
     * Reset the 5-minute schedule for a user.
     *
     * @param User $user
     * @param string $password
     * @param string $folder
     * @return void
     */
    public function resetSchedule(User $user, string $password, string $folder = 'INBOX'): void
    {
        $this->cancelFutureSyncs($user);
        $this->scheduleSync($user, $password, 0, $folder);
    }

    /**
     * Schedule a retry after 30s failure.
     *
     * @param User $user
     * @param string $password
     * @param string $folder
     * @return void
     */
    public function scheduleRetry(User $user, string $password, string $folder = 'INBOX'): void
    {
        // We use a different key or mechanism if we don't want to interfere with the 5-min schedule
        // But the requirement says "retry after 30 seconds or revert to the previous schedule".
        // Let's just dispatch a job with 30s delay without recording it as the "next sync"
        
        $job = (new ImapSyncJob($user, $password, $folder))
            ->delay(30);
        
        dispatch($job);
    }

    /**
     * Trigger a sync based on user action with debounce.
     *
     * @param User $user
     * @param string $password
     * @param string $folder
     * @return void
     */
    public function triggerUserActionSync(User $user, string $password, string $folder = 'INBOX'): void
    {
        $nextSyncJobId = Cache::get("next_sync_job_id_{$user->id}");
        
        if ($nextSyncJobId) {
            $cancelled = $this->cancelFutureSyncs($user);
            if (!$cancelled) {
                // Already have a sync coming up very soon, do nothing
                return;
            }
        }

        // Debounce: check if a sync is already pending in the debounce window
        $debounceKey = "sync_debounce_{$user->id}";
        if (Cache::has($debounceKey)) {
            return;
        }

        Cache::put($debounceKey, true, self::DEBOUNCE_TIME);
        
        $this->scheduleSync($user, $password, self::DEBOUNCE_TIME, $folder);
    }
}
