<?php

namespace Tests\Utils\Traits;

use Closure;
use Mockery\MockInterface;
use Infrastructure\Google2FA\Google2FAService;

trait WithGoogle2FAServiceFake
{
    private ?MockInterface $google2FAServiceFake = null;

    private function setUpGoogle2FAFake(): void
    {
        $this->google2FAServiceFake = $this->mock(Google2FAService::class);
    }

    private function tearDownGoogle2FAFake(): void
    {
        $this->google2FAServiceFake = null;
    }

    protected function expectGoogle2FASecretKeyGenerated(string | Closure $secret): void
    {
        $this->google2FAServiceFake
            ->shouldReceive('generateSecretKey')
            ->once()
            ->andReturnUsing(static fn () => value($secret));
    }

    protected function expectGoogle2FARecoveryCodeGenerated(string | Closure $secret): void
    {
        $this->google2FAServiceFake
            ->shouldReceive('generateRecoveryCode')
            ->once()
            ->andReturnUsing(static fn () => value($secret));
    }

    protected function expectGoogle2FAQrCodeRetrieved(string $holder, string $secretKey, string | Closure $qr): void
    {
        $this->google2FAServiceFake
            ->shouldReceive('getQRCode')
            ->withArgs([$holder, $secretKey])
            ->once()
            ->andReturnUsing(static fn () => value($qr));
    }

    protected function expectGoogle2FAKeyVerified(string $secretKey, string $key, bool | Closure $verified): void
    {
        $this->google2FAServiceFake
            ->shouldReceive('verifyKey')
            ->withArgs([$secretKey, $key])
            ->once()
            ->andReturnUsing(static fn () => value($verified));
    }
}
