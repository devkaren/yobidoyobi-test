<?php

namespace Modules\HealthCheck\Checks;

use Exception;
use Throwable;
use Illuminate\Support\Facades\Cache;
use Modules\HealthCheck\Exceptions\CheckFailedException;

final class CacheCheck extends AbstractCheck
{
    private const CACHE_KEY = 'health_check';
    private const HEALTHY_STATUS = '1';

    public function check(): void
    {
        try {
            Cache::put(self::CACHE_KEY, self::HEALTHY_STATUS, 60);

            if (Cache::pull(self::CACHE_KEY) !== self::HEALTHY_STATUS) {
                throw new Exception('Cache pull error!');
            }
        } catch (Throwable $e) {
            throw new CheckFailedException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
