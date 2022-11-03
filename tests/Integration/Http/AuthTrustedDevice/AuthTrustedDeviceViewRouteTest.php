<?php

namespace Tests\Integration\Http\AuthTrustedDevice;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Infrastructure\Eloquent\Models\UserTrustedDevice;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthTrustedDeviceViewRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testUnauthenticated(): void
    {
        $device = UserTrustedDevice::factory()->create();

        $this
            ->json('GET', "authTrustedDevice/{$device->id}")
            ->assertUnauthorized();
    }

    public function testUserViewsForeignUsersTrustedDevice(): void
    {
        $user = User::factory()->create();
        $device = UserTrustedDevice::factory()->create();

        $this
            ->actingAs($user)
            ->json('GET', "authTrustedDevice/{$device->id}")
            ->assertForbidden();
    }

    public function testUserViewsTrustedDevice(): void
    {
        $device = UserTrustedDevice::factory()->create();

        $this
            ->actingAs($device->user)
            ->json('GET', "authTrustedDevice/{$device->id}")
            ->assertOk()
            ->assertAuthTrustedDeviceSchema($device);
    }
}
