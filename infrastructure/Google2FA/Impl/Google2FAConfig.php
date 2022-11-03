<?php

namespace Infrastructure\Google2FA\Impl;

final class Google2FAConfig
{
    public function __construct(
        public readonly string $company,
    ) {
        //
    }
}
