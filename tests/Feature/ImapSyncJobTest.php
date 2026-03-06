<?php

namespace Tests\Feature;

use App\Jobs\ImapSyncJob;
use App\Models\Email;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Webklex\IMAP\Facades\Client;
use Mockery;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Webklex\PHPIMAP\Support\FlagCollection;

class ImapSyncJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_syncs_emails_from_imap_to_database(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        $password = 'secret';
        
        // Mock IMAP Client
        $clientMock = Mockery::mock('Webklex\PHPIMAP\Client');
        $clientMock->username = $user->email;
        $clientMock->password = $password;
        
        $folderMock = Mockery::mock('Webklex\PHPIMAP\Folder');
        $folderMock->shouldReceive('query->all->get')->once()->andReturn(collect([
            $this->mockMessage(101, 'Subject 1', 'sender1@test.com', 'Body 1'),
            $this->mockMessage(102, 'Subject 2', 'sender2@test.com', 'Body 2'),
        ]));

        $clientMock->shouldReceive('connect')->once()->andReturn($clientMock);
        $clientMock->shouldReceive('getFolder')->with('INBOX')->once()->andReturn($folderMock);
        
        Client::shouldReceive('account')->with('default')->once()->andReturn($clientMock);

        // Run Job
        ImapSyncJob::dispatchSync($user, $password);

        // Assert
        $this->assertDatabaseHas('emails', [
            'user_id' => $user->id,
            'imap_uid' => 101,
            'subject' => 'Subject 1',
        ]);
        $this->assertDatabaseHas('emails', [
            'user_id' => $user->id,
            'imap_uid' => 102,
            'subject' => 'Subject 2',
        ]);
    }

    public function test_it_logs_error_on_sync_failure(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        $password = 'wrong';

        Log::shouldReceive('error')->once();

        $clientMock = Mockery::mock('Webklex\PHPIMAP\Client');
        $clientMock->username = $user->email;
        $clientMock->password = $password;
        $clientMock->shouldReceive('connect')->once()->andThrow(new \Exception('Sync error'));
        
        Client::shouldReceive('account')->with('default')->once()->andReturn($clientMock);

        $this->expectException(\Exception::class);

        ImapSyncJob::dispatchSync($user, $password);
    }

    private function mockMessage($uid, $subject, $from, $body)
    {
        $msg = Mockery::mock('Webklex\PHPIMAP\Message');
        
        $msg->shouldReceive('get')->with('uid')->andReturn($uid);
        $msg->shouldReceive('get')->with('subject')->andReturn($subject);
        $msg->shouldReceive('get')->with('from')->andReturn(collect([ (object)['mail' => $from] ]));
        $msg->shouldReceive('get')->with('to')->andReturn(collect([ (object)['mail' => 'user@example.com'] ]));
        $msg->shouldReceive('get')->with('date')->andReturn(Carbon::now());
        
        $msg->shouldReceive('getFlags')->andReturn(new FlagCollection(['seen']));
        
        $msg->shouldReceive('getHTMLBody')->andReturn($body);
        $msg->shouldReceive('getTextBody')->andReturn($body);
        
        return $msg;
    }
}
