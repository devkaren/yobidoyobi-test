<?php

namespace Tests\Integration\Http\AuthGoogle2FA;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthGoogle2FADisableRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testUnauthenticated(): void
    {
        $this
            ->json('POST', 'authGoogle2FA/disable')
            ->assertUnauthorized();
    }

    public function testDisableNotHavingGoogle_2faSecret(): void
    {
        $user = User::factory()->withoutGoogle2FA()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle2FA/disable')
            ->assertForbidden();
    }

    public function testDisableAlreadyDisabledGoogle_2fa(): void
    {
        $user = User::factory()->create(['google_2fa_enabled' => false]);

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle2FA/disable')
            ->assertForbidden();
    }

    public function testDisable(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle2FA/disable')
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'google_2fa_enabled' => false,
        ]);
    }
}
