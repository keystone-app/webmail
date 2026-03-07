<?php

namespace Tests\Unit\Services;

use App\Jobs\ImapSyncJob;
use App\Models\User;
use App\Services\SyncSchedulerService;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SyncSchedulerServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        Cache::flush();
    }

    public function test_it_schedules_initial_sync_on_login(): void
    {
        $user = User::factory()->create();
        $password = 'secret';
        $service = new SyncSchedulerService();

        $service->scheduleInitialSync($user, $password);

        // Should dispatch initial sync immediately
        Queue::assertPushed(ImapSyncJob::class, function ($job) use ($user, $password) {
            return $job->user->id === $user->id && $job->password === $password && $job->folder === 'INBOX';
        });
        
        // Should have recorded next sync time (approx now for initial sync)
        $this->assertTrue(Cache::has("next_sync_time_{$user->id}"));
        $nextSync = Cache::get("next_sync_time_{$user->id}");
        $this->assertGreaterThanOrEqual(time(), $nextSync);
    }

    public function test_it_records_job_id_and_time_on_schedule(): void
    {
        $user = User::factory()->create();
        $password = 'secret';
        $service = new SyncSchedulerService();

        $service->scheduleSync($user, $password, 300);

        $this->assertTrue(Cache::has("next_sync_job_id_{$user->id}"));
        $this->assertTrue(Cache::has("next_sync_time_{$user->id}"));
        
        Queue::assertPushed(ImapSyncJob::class, function ($job) {
            return $job->delay === 300;
        });
    }

    public function test_it_cancels_future_sync_if_not_within_30s(): void
    {
        $user = User::factory()->create();
        $password = 'secret';
        $service = new SyncSchedulerService();

        // Schedule a sync 5 mins from now
        $service->scheduleSync($user, $password, 300);
        $jobId = Cache::get("next_sync_job_id_{$user->id}");

        $service->cancelFutureSyncs($user);

        $this->assertTrue(Cache::has("cancelled_job_{$jobId}"));
        $this->assertNull(Cache::get("next_sync_job_id_{$user->id}"));
    }

    public function test_it_does_not_cancel_future_sync_if_within_30s(): void
    {
        $user = User::factory()->create();
        $password = 'secret';
        $service = new SyncSchedulerService();

        // Schedule a sync 10s from now
        $service->scheduleSync($user, $password, 10);
        $jobId = Cache::get("next_sync_job_id_{$user->id}");

        $service->cancelFutureSyncs($user);

        $this->assertFalse(Cache::has("cancelled_job_{$jobId}"));
        $this->assertEquals($jobId, Cache::get("next_sync_job_id_{$user->id}"));
    }

    public function test_it_resets_schedule_on_user_action(): void
    {
        $user = User::factory()->create();
        $password = 'secret';
        $service = new SyncSchedulerService();

        // Already have a sync scheduled for 5 mins
        $service->scheduleSync($user, $password, 300);
        $jobId = Cache::get("next_sync_job_id_{$user->id}");

        $service->resetSchedule($user, $password);

        // Previous job should be cancelled
        $this->assertTrue(Cache::has("cancelled_job_{$jobId}"));
        
        // New sync should be scheduled immediately
        Queue::assertPushed(ImapSyncJob::class, function ($job) {
            return $job->delay === 0;
        });
    }

    public function test_it_schedules_retry_on_failure(): void
    {
        $user = User::factory()->create();
        $password = 'secret';
        $service = new SyncSchedulerService();

        $service->scheduleRetry($user, $password);

        Queue::assertPushed(ImapSyncJob::class, function ($job) {
            return $job->delay === 30; // 30s retry
        });
        
        // Retry should NOT update the next_sync_time/id if we want to preserve the 5-min schedule
        // But wait, if it fails, it will keep retrying every 30s?
        // Actually, the 5-min schedule is only reset on SUCCESS.
    }
}
