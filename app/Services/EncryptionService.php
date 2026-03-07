<?php

namespace App\Services;

class EncryptionService
{
    private string $cipher = 'AES-256-CBC';

    /**
     * Encrypt data with a custom key.
     */
    public function encrypt(string $data, string $key): string
    {
        $iv_length = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($iv_length);
        $encrypted = openssl_encrypt($data, $this->cipher, $key, 0, $iv);

        // Combined string of IV and encrypted data (base64)
        return base64_encode($iv . $encrypted);
    }

    /**
     * Decrypt data with a custom key.
     */
    public function decrypt(string $data, string $key): ?string
    {
        $decoded = base64_decode($data);
        $iv_length = openssl_cipher_iv_length($this->cipher);

        $iv = substr($decoded, 0, $iv_length);
        $encrypted = substr($decoded, $iv_length);

        $decrypted = openssl_decrypt($encrypted, $this->cipher, $key, 0, $iv);

        return $decrypted === false ? null : $decrypted;
    }
}
