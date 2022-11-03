<?php

namespace Tests\Integration\Http\AuthTrustedDevice;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Infrastructure\Eloquent\Models\UserTrustedDevice;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthTrustedDeviceQueryRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testUnauthenticated(): void
    {
        $this
            ->json('GET', 'authTrustedDevice')
            ->assertUnauthorized();
    }

    public function testUserRetrievesTrustedDevices(): void
    {
        $user = User::factory()->create();

        $devices = UserTrustedDevice::factory()
            ->for($user)
            ->count(5)
            ->create();

        $this
            ->actingAs($user)
            ->json('GET', 'authTrustedDevice')
            ->assertOk()
            ->assertAuthTrustedDeviceSchemaCollection($devices);
    }
}
