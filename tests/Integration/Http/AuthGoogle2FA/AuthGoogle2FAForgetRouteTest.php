<?php

namespace Tests\Integration\Http\AuthGoogle2FA;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Infrastructure\Eloquent\Models\UserTrustedDevice;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthGoogle2FAForgetRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testUnauthenticated(): void
    {
        $this
            ->json('POST', 'authGoogle2FA/forget')
            ->assertUnauthorized();
    }

    public function testForgetWithoutHavingGoogle_2faSecret(): void
    {
        $user = User::factory()->withoutGoogle2FA()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle2FA/forget')
            ->assertForbidden();
    }

    public function testForgetRemoveTheSecretKeyAndTrustedDevices(): void
    {
        $user = User::factory()
            ->has(UserTrustedDevice::factory()->count(5))
            ->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle2FA/forget')
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'google_2fa_secret' => null,
            'google_2fa_recovery_code' => null,
        ]);

        foreach ($user->userTrustedDevices as $device) {
            $this->assertModelMissing($device);
        }
    }
}
