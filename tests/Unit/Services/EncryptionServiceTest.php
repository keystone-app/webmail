<?php

namespace Tests\Unit\Services;

use App\Services\EncryptionService;
use PHPUnit\Framework\TestCase;

class EncryptionServiceTest extends TestCase
{
    private EncryptionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new EncryptionService();
    }

    public function test_can_encrypt_and_decrypt_with_user_key(): void
    {
        $key = bin2hex(random_bytes(16)); // 32 chars
        $data = 'secret-password';

        $encrypted = $this->service->encrypt($data, $key);
        $this->assertNotEquals($data, $encrypted);

        $decrypted = $this->service->decrypt($encrypted, $key);
        $this->assertEquals($data, $decrypted);
    }

    public function test_cannot_decrypt_with_wrong_key(): void
    {
        $key1 = bin2hex(random_bytes(16));
        $key2 = bin2hex(random_bytes(16));
        $data = 'secret-password';

        $encrypted = $this->service->encrypt($data, $key1);
        $decrypted = $this->service->decrypt($encrypted, $key2);

        $this->assertNotEquals($data, $decrypted);
    }
}
