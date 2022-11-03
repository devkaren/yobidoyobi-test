<?php

namespace Infrastructure\Socialite\Google;

final class GoogleUser
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $nickname,
        public readonly ?string $name,
        public readonly ?string $email,
        public readonly ?string $avatar,
    ) {
        //
    }
}
