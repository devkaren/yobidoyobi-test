<?php

namespace Modules\HealthCheck\Checks;

use Modules\HealthCheck\Exceptions\CheckFailedException;

abstract class AbstractCheck
{
    /**
     * @throws CheckFailedException
     * @return void
     */
    abstract public function check(): void;
}
