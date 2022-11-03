<?php

namespace Infrastructure\Utils;

final class WebUrl
{
    public static function getEmailVerificationUrl(string $email, string $token): string
    {
        return config('web.email_verification_url') . '?' . http_build_query([
            'email' => $email,
            'token' => $token,
        ]);
    }

    public static function getResetPasswordUrl(string $email, string $token): string
    {
        return config('web.reset_password_url') . '?' . http_build_query([
            'email' => $email,
            'token' => $token,
        ]);
    }
}
