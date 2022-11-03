<?php

namespace Infrastructure\Console;

use Illuminate\Console\Scheduling\Schedule;
use Infrastructure\Console\Commands\AppCiCommand;
use Infrastructure\Console\Commands\AppInstallCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * @codeCoverageIgnore
 */
final class Kernel extends ConsoleKernel
{
    protected $commands = [
        AppInstallCommand::class,
        AppCiCommand::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule
            ->command('passport:purge', ['--revoked' => true, '--expired' => true])
            ->dailyAt('23:59')
            ->onOneServer()
            ->runInBackground();

        $schedule
            ->command('auth:clear-resets')
            ->dailyAt('23:59')
            ->onOneServer()
            ->runInBackground();
    }
}
