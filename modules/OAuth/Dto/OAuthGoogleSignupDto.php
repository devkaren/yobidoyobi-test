<?php

namespace Modules\OAuth\Dto;

final class OAuthGoogleSignupDto
{
    public function __construct(
        public readonly string $token,
    ) {
        //
    }
}
