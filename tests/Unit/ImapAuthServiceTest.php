<?php

namespace Tests\Unit;

use App\Services\ImapAuthService;
use Tests\TestCase;
use Webklex\IMAP\Facades\Client;
use Mockery;

class ImapAuthServiceTest extends TestCase
{
    public function test_authenticate_returns_true_for_valid_credentials(): void
    {
        $email = 'test@example.com';
        $password = 'password';

        // Mock the Client facade to simulate a successful connection
        $client = Mockery::mock('Webklex\PHPIMAP\Client');
        $client->shouldReceive('setUsername')->with($email)->once()->andReturn($client);
        $client->shouldReceive('setPassword')->with($password)->once()->andReturn($client);
        $client->shouldReceive('connect')->once()->andReturn($client);
        
        Client::shouldReceive('account')->with('default')->once()->andReturn($client);

        $service = new ImapAuthService();
        $this->assertTrue($service->authenticate($email, $password));
    }

    public function test_authenticate_returns_false_for_invalid_credentials(): void
    {
        $email = 'test@example.com';
        $password = 'wrong_password';

        // Mock the Client facade to simulate a connection failure
        $client = Mockery::mock('Webklex\PHPIMAP\Client');
        $client->shouldReceive('setUsername')->with($email)->once()->andReturn($client);
        $client->shouldReceive('setPassword')->with($password)->once()->andReturn($client);
        $client->shouldReceive('connect')->once()->andThrow(new \Exception('Connection failed'));
        
        Client::shouldReceive('account')->with('default')->once()->andReturn($client);

        $service = new ImapAuthService();
        $this->assertFalse($service->authenticate($email, $password));
    }
}
