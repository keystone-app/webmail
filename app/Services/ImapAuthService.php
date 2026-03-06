<?php

declare(strict_types=1);

namespace App\Services;

use Webklex\IMAP\Facades\Client;
use Exception;

class ImapAuthService
{
    /**
     * Authenticate a user using IMAP.
     *
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function authenticate(string $email, string $password): bool
    {
        try {
            $client = Client::account('default');
            $client->setUsername($email);
            $client->setPassword($password);
            
            $client->connect();
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
