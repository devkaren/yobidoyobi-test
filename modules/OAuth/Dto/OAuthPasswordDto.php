<?php

namespace Modules\OAuth\Dto;

final class OAuthPasswordDto
{
    public function __construct(
        public readonly string $username,
        public readonly string $password,
    ) {
        //
    }
}
