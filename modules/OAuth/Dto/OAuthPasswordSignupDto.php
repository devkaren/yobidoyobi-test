<?php

namespace Modules\OAuth\Dto;

final class OAuthPasswordSignupDto
{
    public function __construct(
        public readonly string $username,
        public readonly string $password,
        public readonly string $firstName,
        public readonly string $lastName,
    ) {
        //
    }
}
