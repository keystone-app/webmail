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
use Illuminate\Support\Facades\Storage;
use Webklex\PHPIMAP\Support\FlagCollection;
use Webklex\PHPIMAP\Support\AttachmentCollection;

class ImapSyncJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_syncs_emails_and_attachments_from_imap_to_database(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['email' => 'user@example.com']);
        $password = 'secret';
        
        // Mock IMAP Client
        $clientMock = Mockery::mock('Webklex\PHPIMAP\Client');
        $clientMock->username = $user->email;
        $clientMock->password = $password;
        
        // Mock Attachment
        $attachmentMock = Mockery::mock('Webklex\PHPIMAP\Attachment');
        $attachmentMock->shouldReceive('getName')->andReturn('test.pdf');
        $attachmentMock->shouldReceive('getContentType')->andReturn('application/pdf');
        $attachmentMock->shouldReceive('getSize')->andReturn(1024);
        $attachmentMock->shouldReceive('getContentId')->andReturn(null);
        $attachmentMock->disposition = 'attachment';
        $attachmentMock->shouldReceive('getContent')->andReturn('dummy content');

        $folderMock = Mockery::mock('Webklex\PHPIMAP\Folder');
        $folderMock->shouldReceive('query->all->get')->once()->andReturn(collect([
            $this->mockMessage(101, 'Subject 1', 'sender1@test.com', 'Body 1', [$attachmentMock]),
        ]));

        $clientMock->shouldReceive('connect')->once()->andReturn($clientMock);
        $clientMock->shouldReceive('getFolder')->with('INBOX')->once()->andReturn($folderMock);
        
        Client::shouldReceive('account')->with('default')->once()->andReturn($clientMock);

        // Run Job
        ImapSyncJob::dispatchSync($user, $password);

        // Assert Email
        $this->assertDatabaseHas('emails', [
            'user_id' => $user->id,
            'imap_uid' => 101,
        ]);
        
        $email = Email::first();
        
        // Assert Attachment
        $this->assertDatabaseHas('attachments', [
            'email_id' => $email->id,
            'filename' => 'test.pdf',
            'content_type' => 'application/pdf',
        ]);

        Storage::disk('public')->assertExists("attachments/{$user->id}/{$email->id}/test.pdf");
    }

    public function test_it_handles_inline_attachments_and_replaces_cid(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['email' => 'user@example.com']);
        $password = 'secret';
        
        // Mock IMAP Client
        $clientMock = Mockery::mock('Webklex\PHPIMAP\Client');
        $clientMock->username = $user->email;
        $clientMock->password = $password;
        
        // Mock Inline Attachment
        $attachmentMock = Mockery::mock('Webklex\PHPIMAP\Attachment');
        $attachmentMock->shouldReceive('getName')->andReturn('image.png');
        $attachmentMock->shouldReceive('getContentType')->andReturn('image/png');
        $attachmentMock->shouldReceive('getSize')->andReturn(512);
        $attachmentMock->shouldReceive('getContentId')->andReturn('img123');
        $attachmentMock->disposition = 'inline';
        $attachmentMock->shouldReceive('getContent')->andReturn('image binary');

        $folderMock = Mockery::mock('Webklex\PHPIMAP\Folder');
        $folderMock->shouldReceive('query->all->get')->once()->andReturn(collect([
            $this->mockMessage(102, 'Subject 2', 'sender2@test.com', 'Hello <img src="cid:img123">', [$attachmentMock]),
        ]));

        $clientMock->shouldReceive('connect')->once()->andReturn($clientMock);
        $clientMock->shouldReceive('getFolder')->with('INBOX')->once()->andReturn($folderMock);
        
        Client::shouldReceive('account')->with('default')->once()->andReturn($clientMock);

        // Run Job
        ImapSyncJob::dispatchSync($user, $password);

        $email = Email::first();
        $attachment = $email->attachments->first();
        
        // Assert cid replaced with proxy URL
        $expectedUrl = route('attachments.show', $attachment->id);
        $this->assertStringContainsString($expectedUrl, $email->body);
        $this->assertStringNotContainsString('cid:img123', $email->body);
    }

    public function test_it_syncs_emails_from_custom_folder(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        $password = 'secret';
        $customFolder = 'INBOX.enviadas';
        
        // Mock IMAP Client
        $clientMock = Mockery::mock('Webklex\PHPIMAP\Client');
        $clientMock->username = $user->email;
        $clientMock->password = $password;

        $folderMock = Mockery::mock('Webklex\PHPIMAP\Folder');
        $folderMock->shouldReceive('query->all->get')->once()->andReturn(collect([
            $this->mockMessage(201, 'Sent Subject', 'user@example.com', 'Sent body'),
        ]));

        $clientMock->shouldReceive('connect')->once()->andReturn($clientMock);
        $clientMock->shouldReceive('getFolder')->with($customFolder)->once()->andReturn($folderMock);
        
        Client::shouldReceive('account')->with('default')->once()->andReturn($clientMock);

        // Run Job with custom folder
        ImapSyncJob::dispatchSync($user, $password, $customFolder);

        // Assert Email is saved with correct folder name
        $this->assertDatabaseHas('emails', [
            'user_id' => $user->id,
            'folder' => $customFolder,
            'imap_uid' => 201,
        ]);
    }

    public function test_it_handles_missing_folder_gracefully(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        $password = 'secret';
        $nonExistentFolder = 'NON_EXISTENT';
        
        // Mock IMAP Client
        $clientMock = Mockery::mock('Webklex\PHPIMAP\Client');
        $clientMock->username = $user->email;
        $clientMock->password = $password;

        $clientMock->shouldReceive('connect')->once()->andReturn($clientMock);
        $clientMock->shouldReceive('getFolder')->with($nonExistentFolder)->once()->andReturn(null);
        
        Client::shouldReceive('account')->with('default')->once()->andReturn($clientMock);

        Log::shouldReceive('warning')->once()->with(Mockery::pattern("/Folder $nonExistentFolder not found/"));

        // Run Job with missing folder
        ImapSyncJob::dispatchSync($user, $password, $nonExistentFolder);
        
        // Should not throw exception
        $this->assertTrue(true);
    }

    public function test_it_logs_error_on_sync_failure(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        $password = 'wrong';

        Log::shouldReceive('warning')->times(3);
        Log::shouldReceive('error')->once();

        $clientMock = Mockery::mock('Webklex\PHPIMAP\Client');
        $clientMock->username = $user->email;
        $clientMock->password = $password;
        $clientMock->shouldReceive('connect')->times(3)->andThrow(new \Exception('Sync error'));
        
        Client::shouldReceive('account')->with('default')->andReturn($clientMock);

        $this->expectException(\Exception::class);

        ImapSyncJob::dispatchSync($user, $password);
    }

    private function mockMessage($uid, $subject, $from, $body, $attachments = [])
    {
        $msg = Mockery::mock('Webklex\PHPIMAP\Message');
        
        $msg->shouldReceive('get')->with('uid')->andReturn($uid);
        $msg->shouldReceive('get')->with('subject')->andReturn($subject);
        $msg->shouldReceive('get')->with('from')->andReturn(collect([ (object)['mail' => $from] ]));
        $msg->shouldReceive('get')->with('to')->andReturn(collect([ (object)['mail' => 'user@example.com'] ]));
        $msg->shouldReceive('get')->with('date')->andReturn(Carbon::now());
        
        $msg->shouldReceive('getFlags')->andReturn(new FlagCollection(['seen']));
        
        $msg->shouldReceive('getHTMLBody')->andReturn($body);
        $msg->shouldReceive('getTextBody')->andReturn(strip_tags($body));
        
        $msg->shouldReceive('getAttachments')->andReturn(new AttachmentCollection(collect($attachments)));
        
        return $msg;
    }
}
