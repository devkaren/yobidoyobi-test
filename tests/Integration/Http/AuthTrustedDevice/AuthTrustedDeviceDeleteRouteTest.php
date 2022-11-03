<?php

namespace Tests\Integration\Http\AuthTrustedDevice;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Infrastructure\Eloquent\Models\UserTrustedDevice;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthTrustedDeviceDeleteRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testUnauthenticated(): void
    {
        $device = UserTrustedDevice::factory()->create();

        $this
            ->json('DELETE', "authTrustedDevice/{$device->id}", [
                'currentPassword' => 'password',
            ])
            ->assertUnauthorized();
    }

    public function testUserDeletesForeignUsersTrustedDevice(): void
    {
        $user = User::factory()->create();
        $device = UserTrustedDevice::factory()->create();

        $this
            ->actingAs($user)
            ->json('DELETE', "authTrustedDevice/{$device->id}", [
                'currentPassword' => 'password',
            ])
            ->assertForbidden();
    }

    public function testUserDeletesTrustedDeviceWithIncorrectCurrentPassword(): void
    {
        $device = UserTrustedDevice::factory()->create();

        $this
            ->actingAs($device->user)
            ->json('DELETE', "authTrustedDevice/{$device->id}", [
                'currentPassword' => $this->faker->uuid(),
            ])
            ->assertUnprocessable();
    }

    public function testUserDeletesTrustedDeviceWithoutCurrentPassword(): void
    {
        $device = UserTrustedDevice::factory()
            ->for(User::factory()->withoutPasswordCredentials())
            ->create();

        $this
            ->actingAs($device->user)
            ->json('DELETE', "authTrustedDevice/{$device->id}", [
                'currentPassword' => null,
            ])
            ->assertOk();

        $this->assertModelMissing($device);
    }

    public function testUserDeletesTrustedDeviceWithCurrentPassword(): void
    {
        $device = UserTrustedDevice::factory()->create();

        $this
            ->actingAs($device->user)
            ->json('DELETE', "authTrustedDevice/{$device->id}", [
                'currentPassword' => 'password',
            ])
            ->assertOk();

        $this->assertModelMissing($device);
    }
}
