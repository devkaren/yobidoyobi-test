<?php

namespace Modules\OAuth\Exceptions;

use League\OAuth2\Server\Exception\OAuthServerException as Exception;

final class OAuthServerException extends Exception
{
    public static function invalidOtp(): static
    {
        return new static(
            'OTP code is missing or incorrect.',
            3,
            'invalid_otp',
            400,
            'Make sure to provide correct one time password',
        );
    }

    public static function invalidOtpRecoveryCode(): static
    {
        return new static(
            'OTP recovery code is incorrect.',
            3,
            'invalid_otp_recovery_code',
            400,
            'Make sure to provide correct one time password recovery code',
        );
    }

    public static function invalidSignupCredentials(): static
    {
        return new static(
            'The signup credentials are already associated with an account.',
            6,
            'invalid_grant',
            400
        );
    }
}
