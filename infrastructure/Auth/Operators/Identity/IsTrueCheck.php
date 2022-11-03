<?php

namespace Infrastructure\Auth\Operators\Identity;

use Closure;
use Illuminate\Auth\Access\Response;
use Infrastructure\Auth\AbstractCheck;

final class IsTrueCheck extends AbstractCheck
{
    public function __construct(
        private readonly bool | Closure $condition,
    ) {
        //
    }

    public function execute(): Response
    {
        return new Response(value($this->condition));
    }
}
