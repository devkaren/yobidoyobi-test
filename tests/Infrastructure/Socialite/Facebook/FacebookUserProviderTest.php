<?php

namespace Tests\Infrastructure\Socialite\Facebook;

use Mockery;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use Laravel\Socialite\Two\AbstractProvider;
use Illuminate\Foundation\Testing\WithFaker;
use Infrastructure\Socialite\Facebook\FacebookUser;
use Infrastructure\Socialite\Facebook\FacebookUserProvider;
use Infrastructure\Socialite\Exceptions\InvalidAccessTokenException;
use Tests\Infrastructure\AbstractInfrastructureTestCase as TestCase;

final class FacebookUserProviderTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Socialite::spy();
    }

    public function testRetrieveFacebookUser(): void
    {
        Socialite::shouldReceive('driver')
            ->withArgs(['facebook'])
            ->once()
            ->andReturn($driver = Mockery::mock(AbstractProvider::class));

        $driver
            ->shouldReceive('userFromToken')
            ->withArgs([$accessToken = $this->faker->sha1()])
            ->once()
            ->andReturn($source = $this->faker->socialiteUser());

        /** @var FacebookUser $user */
        $user = $this->app->make(FacebookUserProvider::class)->request($accessToken);

        $this->assertSame($source->getId(), $user->id);
        $this->assertSame($source->getNickname(), $user->nickname);
        $this->assertSame($source->getName(), $user->name);
        $this->assertSame($source->getEmail(), $user->email);
        $this->assertSame($source->getAvatar(), $user->avatar);
    }

    public function testCatchClientExceptionWith_190ErrorCodeAndThrowInvalidAccessTokenException(): void
    {
        Socialite::shouldReceive('driver')
            ->withArgs(['facebook'])
            ->once()
            ->andReturn($driver = Mockery::mock(AbstractProvider::class));

        $exception = new ClientException('', new Request('POST', ''), new Response(400, body: json_encode([
            'error' => [
                'code' => 190,
                'message' => 'Invalid token',
            ],
        ])));

        $driver
            ->shouldReceive('userFromToken')
            ->withArgs([$accessToken = $this->faker->sha1()])
            ->once()
            ->andReturnUsing(static function () use ($exception) {
                throw $exception;
            });

        $this->expectException(InvalidAccessTokenException::class);

        $this->app->make(FacebookUserProvider::class)->request($accessToken);
    }

    public function testCatchUnknownClientExceptionAndThrowIt(): void
    {
        Socialite::shouldReceive('driver')
            ->withArgs(['facebook'])
            ->once()
            ->andReturn($driver = Mockery::mock(AbstractProvider::class));

        $exception = new ClientException('', new Request('POST', ''), new Response(400));

        $driver
            ->shouldReceive('userFromToken')
            ->withArgs([$accessToken = $this->faker->sha1()])
            ->once()
            ->andReturnUsing(static function () use ($exception) {
                throw $exception;
            });

        $this->expectExceptionObject($exception);

        $this->app->make(FacebookUserProvider::class)->request($accessToken);
    }
}
