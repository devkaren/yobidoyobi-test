<?php

namespace Infrastructure\Socialite\Google;

use Laravel\Socialite\AbstractUser;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Exception\ClientException;
use Infrastructure\Socialite\Exceptions\InvalidAccessTokenException;

final class GoogleUserProvider
{
    public function request(string $accessToken): GoogleUser
    {
        return Cache::remember("google_user_provider_{$accessToken}", 30, static function () use ($accessToken) {
            try {
                /** @var AbstractUser $source */
                $source = Socialite::driver('google')->userFromToken($accessToken);

                return new GoogleUser(
                    $source->getId(),
                    $source->getNickname(),
                    $source->getName(),
                    $source->getEmail(),
                    $source->getAvatar(),
                );
            } catch (ClientException $e) {
                if ($e->getResponse()->getStatusCode() === 401) {
                    throw new InvalidAccessTokenException($e->getMessage(), (int) $e->getCode(), $e);
                }

                throw $e;
            }
        });
    }
}
