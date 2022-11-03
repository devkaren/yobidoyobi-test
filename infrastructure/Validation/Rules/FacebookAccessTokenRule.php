<?php

namespace Infrastructure\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;
use Infrastructure\Socialite\Facebook\FacebookUserProvider;
use Infrastructure\Socialite\Exceptions\InvalidAccessTokenException;

final class FacebookAccessTokenRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        try {
            app(FacebookUserProvider::class)->request($value);

            return true;
        } catch (InvalidAccessTokenException) {
            return false;
        }
    }

    public function message(): string
    {
        return trans('validation.facebook_access_token');
    }
}
