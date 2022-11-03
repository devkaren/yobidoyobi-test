<?php

namespace Tests\Integration\Http\AuthGoogle2FA;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Infrastructure\Eloquent\Models\UserTrustedDevice;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthGoogle2FAEnableRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testUnauthenticated(): void
    {
        $this
            ->json('POST', 'authGoogle2FA/enable')
            ->assertUnauthorized();
    }

    public function testEnableNotHavingGoogle_2faSecret(): void
    {
        $user = User::factory()
            ->withoutGoogle2FA()
            ->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle2FA/enable', [
                'otp' => null,
                'trusted' => true,
            ])
            ->assertForbidden();
    }

    public function testEnableAlreadyEnabledGoogle_2fa(): void
    {
        $user = User::factory()->create();
        $otp = $this->faker->otp();

        $this->expectGoogle2FAKeyVerified($user->google_2fa_secret, $otp, true);

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle2FA/enable', ['otp' => $otp, 'trusted' => true])
            ->assertForbidden();
    }

    public function testEnableWithInvalidOtp(): void
    {
        $user = User::factory()->create(['google_2fa_enabled' => false]);
        $otp = $this->faker->otp();

        $this->expectGoogle2FAKeyVerified($user->google_2fa_secret, $otp, false);

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle2FA/enable', ['otp' => $otp, 'trusted' => true])
            ->assertUnprocessable();
    }

    public function testEnableWithoutAddingCurrentTrustedDevice(): void
    {
        $user = User::factory()->create(['google_2fa_enabled' => false]);
        $otp = $this->faker->otp();

        $this->expectGoogle2FAKeyVerified($user->google_2fa_secret, $otp, true);

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle2FA/enable', [
                'otp' => $otp,
                'trusted' => false,
            ])
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'google_2fa_enabled' => true,
        ]);

        $this->assertDatabaseCount('user_trusted_devices', 0);
    }

    public function testEnableAddTrustedDevice(): void
    {
        $user = User::factory()->create(['google_2fa_enabled' => false]);
        $otp = $this->faker->otp();
        $now = $this->fakeCurrentTimestamp();

        $this->expectGoogle2FAKeyVerified($user->google_2fa_secret, $otp, true);

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle2FA/enable', [
                'otp' => $otp,
                'trusted' => true,
            ])
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'google_2fa_enabled' => true,
        ]);

        $this->assertDatabaseHas('user_trusted_devices', [
            'user_id' => $user->id,
            'ip' => '127.0.0.1',
            'user_agent' => 'Symfony',
            'valid_to' => $now->addMonth(),
        ]);
    }

    public function testEnableProlongExpiredTrustedDevice(): void
    {
        $user = User::factory()->create(['google_2fa_enabled' => false]);

        $device = UserTrustedDevice::factory()
            ->expired()
            ->current()
            ->create(['user_id' => $user->id]);

        $otp = $this->faker->otp();
        $now = $this->fakeCurrentTimestamp();

        $this->expectGoogle2FAKeyVerified($user->google_2fa_secret, $otp, true);

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle2FA/enable', [
                'otp' => $otp,
                'trusted' => true,
            ])
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'google_2fa_enabled' => true,
        ]);

        $this->assertDatabaseHas('user_trusted_devices', [
            'id' => $device->id,
            'user_id' => $user->id,
            'ip' => '127.0.0.1',
            'user_agent' => 'Symfony',
            'valid_to' => $now->addMonth(),
        ]);
    }
}
