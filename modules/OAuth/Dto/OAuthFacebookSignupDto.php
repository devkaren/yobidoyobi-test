<?php

namespace Modules\OAuth\Dto;

final class OAuthFacebookSignupDto
{
    public function __construct(
        public readonly string $token,
    ) {
        //
    }
}
