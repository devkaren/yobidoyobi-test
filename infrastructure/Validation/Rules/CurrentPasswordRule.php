<?php

namespace Infrastructure\Validation\Rules;

use Illuminate\Support\Facades\Hash;
use Infrastructure\Eloquent\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ImplicitRule;

final class CurrentPasswordRule implements Rule, ImplicitRule
{
    public function __construct(
        private readonly ?User $user = null,
    ) {
        //
    }

    public function passes($attribute, $value): bool
    {
        if (!$this->user || is_null($this->user->password)) {
            return is_null($value);
        }

        if (!is_string($value)) {
            return false;
        }

        return Hash::check($value, $this->user->password);
    }

    public function message(): string
    {
        return trans('validation.current_password');
    }
}
