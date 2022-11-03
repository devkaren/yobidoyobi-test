<?php

namespace Infrastructure\Google2FA;

interface Google2FAService
{
    /**
     * Generate Google2FA secret key.
     *
     * @return string
     */
    public function generateSecretKey(): string;

    /**
     * Generate Google2FA recovery code.
     *
     * @return string
     */
    public function generateRecoveryCode(): string;

    /**
     * Get QR code using against the secret key.
     *
     * @param  string $holder
     * @param  string $secretKey
     * @return string
     */
    public function getQRCode(string $holder, string $secretKey): string;

    /**
     * Verify key against the secret key.
     *
     * @param  string $secretKey
     * @param  string $key
     * @return bool
     */
    public function verifyKey(string $secretKey, string $key): bool;
}
