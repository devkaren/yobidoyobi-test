<?php

namespace Modules\OAuth\Dto;

final class OAuthFacebookDto
{
    public function __construct(
        public readonly string $token,
    ) {
        //
    }
}
