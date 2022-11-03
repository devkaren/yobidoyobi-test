<?php

namespace Tests\Infrastructure\Google2FA\Impl;

use Mockery;
use Illuminate\Support\Str;
use PragmaRX\Google2FAQRCode\Google2FA;
use PragmaRX\Google2FAQRCode\QRCode\Bacon;
use Illuminate\Foundation\Testing\WithFaker;
use Infrastructure\Google2FA\Impl\Google2FAConfig;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use Infrastructure\Google2FA\Impl\Google2FAService;
use PragmaRX\Google2FA\Exceptions\Google2FAException;
use Tests\Infrastructure\AbstractInfrastructureTestCase as TestCase;
use Infrastructure\Google2FA\Google2FAService as Google2FAServiceContract;

final class Google2FAServiceTest extends TestCase
{
    use WithFaker;

    public function testBindUsingBaconImagickBackendAndCompanyUsingServicesConfig(): void
    {
        /** @var Google2FAService $service */
        $service = $this->app->make(Google2FAServiceContract::class);

        $this->assertInstanceOf(Google2FAService::class, $service);

        /** @var Bacon $qr */
        $qr = $service->service->getQrCodeService();

        $this->assertInstanceOf(Bacon::class, $qr);
        $this->assertInstanceOf(ImagickImageBackEnd::class, $qr->getImageBackend());

        $this->assertSame(config('services.google2fa.company'), $service->config->company);
    }

    public function testGenerate_32LengthSecretKey(): void
    {
        $service = new Google2FAService(
            $mock = Mockery::mock(Google2FA::class),
            new Google2FAConfig($this->faker->company()),
        );

        $mock
            ->shouldReceive('generateSecretKey')
            ->withArgs([32])
            ->once()
            ->andReturn($secret = Str::random(32));

        $this->assertSame($secret, $service->generateSecretKey());
    }

    public function testGenerateRecoveryCodeWith_4Numbers_3BlocksAndDashSeparator(): void
    {
        /** @var Google2FAService $service */
        $service = $this->app->make(Google2FAServiceContract::class);
        $recoveryCode = $service->generateRecoveryCode();

        $this->assertMatchesRegularExpression('/[0-9]{4}\-[0-9]{4}\-[0-9]{4}/', $recoveryCode);
    }

    public function testGetQrCodeWithCompanyConfig(): void
    {
        $service = new Google2FAService(
            $mock = Mockery::mock(Google2FA::class),
            $config = new Google2FAConfig($this->faker->company()),
        );

        $holder = $this->faker->email();
        $secretKey = $this->faker->sha1();

        $mock
            ->shouldReceive('getQRCodeInline')
            ->withArgs([$config->company, $holder, $secretKey])
            ->once()
            ->andReturn($qr = $this->faker->sha1());

        $this->assertSame($qr, $service->getQRCode($holder, $secretKey));
    }

    public function testVerifyKeyCatchGoogle2faException(): void
    {
        $service = new Google2FAService(
            $mock = Mockery::mock(Google2FA::class),
            new Google2FAConfig($this->faker->company()),
        );

        $secretKey = $this->faker->sha1();
        $key = $this->faker->sha1();

        $mock
            ->shouldReceive('verifyKey')
            ->withArgs([$secretKey, $key, 2])
            ->once()
            ->andReturnUsing(static function () {
                throw new Google2FAException();
            });

        $this->assertFalse($service->verifyKey($secretKey, $key));
    }
    public function testVerifyKey(): void
    {
        $service = new Google2FAService(
            $mock = Mockery::mock(Google2FA::class),
            new Google2FAConfig($this->faker->company()),
        );

        $secretKey = $this->faker->sha1();
        $key = $this->faker->sha1();

        $mock
            ->shouldReceive('verifyKey')
            ->withArgs([$secretKey, $key, 2])
            ->once()
            ->andReturn($result = $this->faker->boolean());

        $this->assertSame($result, $service->verifyKey($secretKey, $key));
    }
}
