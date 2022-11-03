<?php

namespace Modules\HealthCheck;

use Modules\HealthCheck\Checks\LogCheck;
use Modules\HealthCheck\Checks\CacheCheck;
use Modules\HealthCheck\Checks\StorageCheck;
use Modules\HealthCheck\Checks\DatabaseCheck;
use Illuminate\Contracts\Foundation\Application;
use Modules\HealthCheck\Services\HealthCheckService;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app
            ->when(HealthCheckService::class)
            ->needs('$healthChecks')
            ->give(static function (Application $app) {
                return [
                    $app->make(DatabaseCheck::class),
                    $app->make(CacheCheck::class),
                    $app->make(LogCheck::class),
                    $app->make(StorageCheck::class),
                ];
            });
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
    }
}
