<?php

namespace Infrastructure\Faker\Providers;

use Faker\Generator;
use Faker\Provider\Base as Provider;

abstract class AbstractProvider
{
    abstract public function provide(Generator $generator): Provider;
}
