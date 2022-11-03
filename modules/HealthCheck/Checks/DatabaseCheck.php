<?php

namespace Modules\HealthCheck\Checks;

use Throwable;
use Illuminate\Support\Facades\DB;
use Modules\HealthCheck\Exceptions\CheckFailedException;

final class DatabaseCheck extends AbstractCheck
{
    public function check(): void
    {
        try {
            DB::connection()->getPdo();
        } catch (Throwable $e) {
            throw new CheckFailedException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
