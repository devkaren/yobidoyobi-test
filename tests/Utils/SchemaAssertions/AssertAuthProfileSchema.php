<?php

namespace Tests\Utils\SchemaAssertions;

use Closure;
use Illuminate\Testing\TestResponse;
use Infrastructure\Eloquent\Models\User;

final class AssertAuthProfileSchema extends AssertSchema
{
    public function assert(): Closure
    {
        return function (User $user, string $container = 'data'): TestResponse {
            return $this->response
                ->assertJsonPath("{$container}.id", $user->id)
                ->assertJsonPath("{$container}.firstName", $user->first_name)
                ->assertJsonPath("{$container}.lastName", $user->last_name)
                ->assertJsonPath("{$container}.email", $user->email)
                ->assertJsonPath("{$container}.hasPassword", (bool) $user->password)
                ->assertJsonPath("{$container}.hasGoogle", (bool) $user->google_id)
                ->assertJsonPath("{$container}.hasGoogle2FA", (bool) $user->google_2fa_secret)
                ->assertJsonPath("{$container}.hasGoogle2FAEnabled", $user->google_2fa_enabled)
                ->assertJsonPath("{$container}.hasFacebook", (bool) $user->facebook_id)
                ->assertJsonPath("{$container}.emailVerifiedAt", $user->email_verified_at?->toRfc3339String())
                ->assertJsonPath("{$container}.passwordChangedAt", $user->password_changed_at?->toRfc3339String())
                ->assertJsonPath("{$container}.registeredAt", $user->registered_at->toRfc3339String());
        };
    }
}
