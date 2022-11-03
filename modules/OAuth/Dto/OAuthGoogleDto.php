<?php

namespace Modules\OAuth\Dto;

final class OAuthGoogleDto
{
    public function __construct(
        public readonly string $token,
    ) {
        //
    }
}
