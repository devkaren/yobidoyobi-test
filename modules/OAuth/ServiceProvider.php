<?php

namespace Modules\OAuth;

use Laravel\Passport\Passport;
use Modules\OAuth\Grants\GoogleGrant;
use Modules\OAuth\Grants\FacebookGrant;
use Modules\OAuth\Grants\PasswordGrant;
use Laravel\Passport\Bridge\UserRepository;
use Modules\OAuth\Grants\GoogleSignupGrant;
use League\OAuth2\Server\AuthorizationServer;
use Modules\OAuth\Grants\FacebookSignupGrant;
use Modules\OAuth\Grants\PasswordSignupGrant;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

final class ServiceProvider extends BaseServiceProvider
{
    public array $bindings = [
        UserRepositoryInterface::class => UserRepository::class,
        RefreshTokenRepositoryInterface::class => RefreshTokenRepository::class,
    ];

    protected array $grants = [
        PasswordGrant::class,
        PasswordSignupGrant::class,
        GoogleGrant::class,
        GoogleSignupGrant::class,
        FacebookGrant::class,
        FacebookSignupGrant::class,
    ];

    public function register(): void
    {
        $this->app->extend(AuthorizationServer::class, function (AuthorizationServer $server): AuthorizationServer {
            foreach ($this->grants as $grant) {
                $server->enableGrantType(app($grant), Passport::tokensExpireIn());
            }

            return $server;
        });
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');

        Passport::tokensCan(config('passport.scopes'));
        Passport::tokensExpireIn(now()->addDays(config('passport.tokens_expire_in')));
        Passport::personalAccessTokensExpireIn(now()->addDays(config('passport.personal_access_tokens_expire_in')));
        Passport::refreshTokensExpireIn(now()->addDays(config('passport.refresh_tokens_expire_in')));
    }
}
