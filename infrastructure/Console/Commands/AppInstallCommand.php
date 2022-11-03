<?php

namespace Infrastructure\Console\Commands;

use Illuminate\Console\Command;

/**
 * @codeCoverageIgnore
 */
final class AppInstallCommand extends Command
{
    protected $signature = 'app:install';

    protected $description = 'Project initial setup command.';

    public function handle(): int
    {
        $this->call('config:clear');
        $this->call('cache:clear');
        $this->call('route:clear');
        $this->call('key:generate', ['--force' => true]);
        $this->call('storage:link');
        $this->call('l5-swagger:generate');
        $this->call('migrate:fresh', ['--force' => true, '--seed' => true]);
        $this->call('passport:install');

        return 0;
    }
}
