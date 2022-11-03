<?php

namespace Infrastructure\Auth;

use Illuminate\Auth\Access\Response;

abstract class AbstractCheck
{
    use CheckProxy;

    abstract public function execute(): Response;
}
