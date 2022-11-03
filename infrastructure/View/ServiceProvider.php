<?php

namespace Infrastructure\View;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        Blade::componentNamespace('Infrastructure\\View\\Components', 'app');
    }
}
