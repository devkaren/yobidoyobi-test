<?php

namespace Infrastructure\Console\Commands;

use Illuminate\Console\Command;

/**
 * @codeCoverageIgnore
 */
final class AppCiCommand extends Command
{
    protected $signature = 'app:ci {--p|production}';

    protected $description = 'Continuous integration command';

    public function handle(): int
    {
        if ($this->option('production')) {
            $this->call('config:cache');
            $this->call('cache:clear');
            $this->call('optimize');
            $this->call('route:cache');
            $this->call('view:cache');
        } else {
            $this->call('optimize:clear');
            $this->call('cache:clear');
            $this->call('route:clear');
            $this->call('view:clear');
        }

        $this->call('migrate', ['--force' => true, '--seed' => true]);
        $this->call('l5-swagger:generate');

        return 0;
    }
}
