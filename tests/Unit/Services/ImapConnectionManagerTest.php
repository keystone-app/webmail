<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\ImapConnectionManager;
use Mockery;
use Tests\TestCase;
use Webklex\IMAP\Facades\Client;
use Webklex\PHPIMAP\Client as ImapClient;

class ImapConnectionManagerTest extends TestCase
{
    public function test_it_can_connect_to_imap(): void
    {
        $user = User::factory()->make(['email' => 'test@example.com']);
        $password = 'secret';

        $clientMock = Mockery::mock(ImapClient::class);
        $clientMock->username = '';
        $clientMock->password = '';
        
        $clientMock->shouldReceive('connect')->once()->andReturnSelf();
        
        Client::shouldReceive('account')->with('default')->once()->andReturn($clientMock);

        $manager = new ImapConnectionManager();
        $connectedClient = $manager->connect($user, $password);

        $this->assertEquals($user->email, $clientMock->username);
        $this->assertEquals($password, $clientMock->password);
        $this->assertSame($clientMock, $connectedClient);
    }

    public function test_it_throws_exception_on_connection_failure(): void
    {
        $user = User::factory()->make(['email' => 'test@example.com']);
        $password = 'secret';

        $clientMock = Mockery::mock(ImapClient::class);
        $clientMock->username = '';
        $clientMock->password = '';
        
        $clientMock->shouldReceive('connect')->once()->andThrow(new \Exception('Connection failed'));
        
        Client::shouldReceive('account')->with('default')->once()->andReturn($clientMock);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Connection failed');

        $manager = new ImapConnectionManager();
        $manager->connect($user, $password);
    }
}
