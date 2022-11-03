<?php

namespace Modules\HealthCheck\Services;

use Modules\HealthCheck\Checks\AbstractCheck;

final class HealthCheckService
{
    /**
     * @param  AbstractCheck[] $healthChecks
     * @return void
     */
    public function __construct(
        private array $healthChecks,
    ) {
        //
    }

    public function healthCheck(): void
    {
        foreach ($this->healthChecks as $health) {
            $health->check();
        }
    }
}
