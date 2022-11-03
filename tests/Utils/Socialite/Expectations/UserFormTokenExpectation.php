<?php

namespace Tests\Utils\Socialite\Expectations;

use Faker\Generator;
use Mockery\Expectation;
use Laravel\Socialite\Two\User;
use Mockery\CompositeExpectation;

abstract class UserFormTokenExpectation
{
    final public function __construct(
        protected readonly Expectation | CompositeExpectation $expectation,
    ) {
        //
    }

    final public function andReturnUser(array | User $user = []): User
    {
        $user = is_array($user)
            ? app(Generator::class)->socialiteUser($user)
            : $user;

        $this->expectation->andReturn($user);

        return $user;
    }
}
