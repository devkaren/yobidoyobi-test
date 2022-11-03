<?php

namespace Infrastructure\Http\Middlewares;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

/**
 * @codeCoverageIgnore
 */
final class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array
     */
    public function hosts()
    {
        return [
            $this->allSubdomainsOfApplicationUrl(),
        ];
    }
}
