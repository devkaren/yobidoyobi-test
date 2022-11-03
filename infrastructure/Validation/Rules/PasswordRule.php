<?php

namespace Infrastructure\Validation\Rules;

use Illuminate\Validation\Rules\Password;

final class PasswordRule extends Password
{
    protected $min = 8;
    protected $mixedCase = true;
    protected $letters = true;
    protected $numbers = true;
    protected $symbols = true;

    public function __construct()
    {
        //
    }
}
