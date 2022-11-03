<?php

namespace Modules\OAuth\Dto;

final class OAuthVerifyOtpDto
{
    public function __construct(
        public readonly int $userId,
        public readonly ?string $otp,
        public readonly ?string $otpRecoveryCode,
        public readonly string $ip,
        public readonly ?string $userAgent,
        public readonly bool $trusted,
    ) {
        //
    }
}
