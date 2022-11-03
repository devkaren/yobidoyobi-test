<?php

namespace Infrastructure\Faker\Providers;

use Faker\Generator;
use Laravel\Socialite\Two\User;
use Faker\Provider\Base as Provider;

final class SocialiteProvider extends AbstractProvider
{
    public function provide(Generator $generator): Provider
    {
        return new class($generator) extends Provider {
            public function socialiteUser(array $attributes = []): User
            {
                $user = new User();

                $user->user = [
                    'id' => $this->generator->uuid(),
                    'nickname' => $this->generator->userName(),
                    'name' => $this->generator->userName(),
                    'email' => $this->generator->unique()->safeEmail(),
                    'avatar' => $this->generator->imageUrl(),
                    ...$attributes,
                ];

                return $user->map($user->user);
            }
        };
    }
}
