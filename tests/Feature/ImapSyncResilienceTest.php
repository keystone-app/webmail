<?php

namespace Tests\Feature;

use App\Jobs\ImapSyncJob;
use App\Models\User;
use App\Services\ImapConnectionManager;
use App\Services\ImapMessageParser;
use App\Services\ImapMessageRepository;
use Exception;
use Mockery;
use Tests\TestCase;

class ImapSyncResilienceTest extends TestCase
{
    public function test_it_retries_on_connection_failure(): void
    {
        $user = User::factory()->create();
        $password = 'secret';

        $connectionManagerMock = Mockery::mock(ImapConnectionManager::class);
        $parserMock = Mockery::mock(ImapMessageParser::class);
        $repositoryMock = Mockery::mock(ImapMessageRepository::class);

        $clientMock = Mockery::mock('Webklex\PHPIMAP\Client');
        $folderMock = Mockery::mock('Webklex\PHPIMAP\Folder');
        
        // Mock enough of the client to not crash on second call
        $clientMock->shouldReceive('getFolder')->andReturn($folderMock);
        $folderMock->shouldReceive('query->all->get')->andReturn(collect([]));
        $repositoryMock->shouldReceive('deleteMissing')->andReturn(0);

        // Fail once, succeed on retry
        $connectionManagerMock->shouldReceive('connect')
            ->twice()
            ->andReturnUsing(function() use ($clientMock) {
                static $count = 0;
                $count++;
                if ($count === 1) {
                    throw new Exception('Connection reset by peer');
                }
                return $clientMock;
            });

        $job = new ImapSyncJob($user, $password);
        
        $job->handle($connectionManagerMock, $parserMock, $repositoryMock);

        $connectionManagerMock->shouldHaveReceived('connect')->twice();
    }
}
