<?php

namespace Infrastructure\Faker\Providers;

use Faker\Generator;
use Faker\Provider\Base as Provider;

final class OtpProvider extends AbstractProvider
{
    public function provide(Generator $generator): Provider
    {
        return new class($generator) extends Provider {
            public function otp(): string
            {
                return $this->generator->numerify('######');
            }
        };
    }
}
