<?php

namespace Infrastructure\Validation\Rules;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Infrastructure\Google2FA\Google2FAService;
use Illuminate\Contracts\Validation\ImplicitRule;

final class Google2FAOtpRule implements Rule, ImplicitRule
{
    public function __construct(
        private readonly ?User $user = null,
    ) {
        //
    }

    public function passes($attribute, $value): bool
    {
        if (!$this->user || is_null($this->user->google_2fa_secret)) {
            return is_null($value);
        }

        if (!is_string($value) || mb_strlen($value) !== 6) {
            return false;
        }

        return app(Google2FAService::class)->verifyKey($this->user->google_2fa_secret, $value);
    }

    public function message(): string
    {
        return trans('validation.google_2fa_otp');
    }
}
