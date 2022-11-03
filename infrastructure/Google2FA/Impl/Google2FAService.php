<?php

namespace Infrastructure\Google2FA\Impl;

use PragmaRX\Recovery\Recovery;
use PragmaRX\Google2FAQRCode\Google2FA;
use PragmaRX\Google2FA\Exceptions\Google2FAException;
use Infrastructure\Google2FA\Google2FAService as Contract;

final class Google2FAService implements Contract
{
    public function __construct(
        public readonly Google2FA $service,
        public readonly Google2FAConfig $config,
    ) {
        //
    }

    public function generateSecretKey(): string
    {
        return $this->service->generateSecretKey(32);
    }

    public function generateRecoveryCode(): string
    {
        return (new Recovery())
            ->setCount(1)
            ->setChars(4)
            ->setBlocks(3)
            ->setBlockSeparator('-')
            ->numeric()
            ->toArray()[0];
    }

    public function getQRCode(string $holder, string $secretKey): string
    {
        return $this->service->getQRCodeInline(
            $this->config->company,
            $holder,
            $secretKey,
        );
    }

    public function verifyKey(string $secretKey, string $key): bool
    {
        try {
            return $this->service->verifyKey($secretKey, $key, 2);
        } catch (Google2FAException) {
            return false;
        }
    }
}
