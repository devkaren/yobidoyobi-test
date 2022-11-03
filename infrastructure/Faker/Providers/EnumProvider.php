<?php

namespace Infrastructure\Faker\Providers;

use Faker\Generator;
use Faker\Provider\Base as Provider;

final class EnumProvider extends AbstractProvider
{
    public function provide(Generator $generator): Provider
    {
        return new class($generator) extends Provider {
            /**
             * @template T
             * @param  class-string<T> $enum
             * @return T
             */
            public function enum(string $enum)
            {
                return $this->generator->randomElement($enum::cases());
            }
        };
    }
}
