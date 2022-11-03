<?php

namespace Infrastructure\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;
use Infrastructure\Socialite\Google\GoogleUserProvider;
use Infrastructure\Socialite\Exceptions\InvalidAccessTokenException;

final class GoogleAccessTokenRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        try {
            app(GoogleUserProvider::class)->request($value);

            return true;
        } catch (InvalidAccessTokenException) {
            return false;
        }
    }

    public function message(): string
    {
        return trans('validation.google_access_token');
    }
}
