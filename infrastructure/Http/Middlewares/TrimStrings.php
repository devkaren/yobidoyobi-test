<?php

namespace Infrastructure\Http\Middlewares;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

final class TrimStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array
     */
    protected $except = [
        'currentPassword',
        'password',
        'password_confirmation',
    ];
}
