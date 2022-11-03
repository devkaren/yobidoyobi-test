<?php

namespace Tests\Integration\Http\AuthProfile;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthProfileViewRouteTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function testUnauthenticated(): void
    {
        $this
            ->json('GET', 'authProfile')
            ->assertUnauthorized();
    }

    public function testSuccessful(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->json('GET', 'authProfile')
            ->assertOk()
            ->assertAuthProfileSchema($user);
    }
}
