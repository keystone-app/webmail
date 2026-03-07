<?php

namespace Tests\Feature;

use App\Jobs\ImapSyncJob;
use App\Models\User;
use App\Services\SyncSchedulerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Webklex\IMAP\Facades\Client;
use Mockery;

class ImapSyncSchedulingIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }

    public function test_it_skips_execution_if_job_is_cancelled(): void
    {
        $user = User::factory()->create();
        $password = 'secret';
        $syncInstanceId = 'test-id';
        
        Cache::put("cancelled_job_{$syncInstanceId}", true);

        // We don't expect any IMAP connection if job is cancelled
        Client::shouldReceive('account')->never();

        $job = new ImapSyncJob($user, $password, 'INBOX', $syncInstanceId);
        
        // Use app()->call to resolve dependencies for the handle method
        app()->call([$job, 'handle']);

        $this->assertFalse(Cache::has("cancelled_job_{$syncInstanceId}"));
    }

    public function test_it_schedules_next_sync_on_success(): void
    {
        $user = User::factory()->create();
        $password = 'secret';
        
        // Mock successful sync
        $clientMock = Mockery::mock('Webklex\PHPIMAP\Client');
        $clientMock->username = $user->email;
        $clientMock->password = $password;
        $clientMock->shouldReceive('connect')->once()->andReturnSelf();
        
        $folderMock = Mockery::mock('Webklex\PHPIMAP\Folder');
        $folderMock->shouldReceive('query->all->get')->once()->andReturn(collect([]));
        
        $clientMock->shouldReceive('getFolder')->with('INBOX')->once()->andReturn($folderMock);
        Client::shouldReceive('account')->with('default')->once()->andReturn($clientMock);

        $job = new ImapSyncJob($user, $password, 'INBOX');
        app()->call([$job, 'handle']);

        // Assert next sync is scheduled (5 mins = 300s)
        Queue::assertPushed(ImapSyncJob::class, function ($job) {
            return $job->delay === 300;
        });
    }

    public function test_it_schedules_retry_on_failure(): void
    {
        $user = User::factory()->create();
        $password = 'secret';

        $clientMock = Mockery::mock('Webklex\PHPIMAP\Client');
        $clientMock->username = $user->email;
        $clientMock->password = $password;
        // Fail 3 times (max attempts)
        $clientMock->shouldReceive('connect')->times(3)->andThrow(new \Exception('Sync error'));
        
        Client::shouldReceive('account')->with('default')->andReturn($clientMock);

        $job = new ImapSyncJob($user, $password, 'INBOX');
        
        try {
            app()->call([$job, 'handle']);
        } catch (\Exception $e) {
            // Expected
        }

        // Assert retry is scheduled (30s)
        Queue::assertPushed(ImapSyncJob::class, function ($job) {
            return $job->delay === 30;
        });
    }
}
