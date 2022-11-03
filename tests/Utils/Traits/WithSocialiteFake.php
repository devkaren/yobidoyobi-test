<?php

namespace Tests\Utils\Traits;

use Mockery;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;
use Tests\Utils\Socialite\Expectations\GoogleUserFormTokenExpectation;
use Tests\Utils\Socialite\Expectations\FacebookUserFormTokenExpectation;

trait WithSocialiteFake
{
    private function setUpSocialiteFake(): void
    {
        Socialite::spy();
    }

    private function tearDownSocialiteFake(): void
    {
        //
    }

    protected function expectGoogleUserFetched(string $accessToken): GoogleUserFormTokenExpectation
    {
        Socialite::shouldReceive('driver')
            ->withArgs(['google'])
            ->once()
            ->andReturn($driver = Mockery::mock(AbstractProvider::class));

        return new GoogleUserFormTokenExpectation(
            $driver
                ->shouldReceive('userFromToken')
                ->withArgs([$accessToken])
                ->once()
        );
    }

    protected function expectFacebookUserFetched(string $accessToken): FacebookUserFormTokenExpectation
    {
        Socialite::shouldReceive('driver')
            ->withArgs(['facebook'])
            ->once()
            ->andReturn($driver = Mockery::mock(AbstractProvider::class));

        return new FacebookUserFormTokenExpectation(
            $driver
                ->shouldReceive('userFromToken')
                ->withArgs([$accessToken])
                ->once()
        );
    }
}
