<?php

namespace App\Services;

use App\Models\User;
use Webklex\IMAP\Facades\Client;
use Webklex\PHPIMAP\Client as ImapClient;

class ImapConnectionManager
{
    /**
     * Connect to the IMAP server for a specific user.
     *
     * @param User $user
     * @param string $password
     * @param string $account
     * @return ImapClient
     */
    public function connect(User $user, string $password, string $account = 'default'): ImapClient
    {
        $client = Client::account($account);
        $client->username = $user->email;
        $client->password = $password;

        $client->connect();

        return $client;
    }
}
