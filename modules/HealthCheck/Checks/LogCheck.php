<?php

namespace Modules\HealthCheck\Checks;

use Throwable;
use Illuminate\Support\Facades\Log;
use Modules\HealthCheck\Exceptions\CheckFailedException;

final class LogCheck extends AbstractCheck
{
    private const CONTENTS = 'health_check';

    public function check(): void
    {
        try {
            Log::info(self::CONTENTS);
        } catch (Throwable $e) {
            throw new CheckFailedException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
