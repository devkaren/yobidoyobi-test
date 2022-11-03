<?php

namespace Tests\Integration\Http\AuthGoogle;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthGoogleForgetRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testUnauthenticated(): void
    {
        $this
            ->json('POST', 'authGoogle/forget')
            ->assertUnauthorized();
    }

    public function testForgetForUserWithoutGoogleLinked(): void
    {
        $user = User::factory()->withoutGoogleCredentials()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle/forget')
            ->assertForbidden();
    }

    public function testForgetForUserWithoutAnyOtherCredentialsLinked(): void
    {
        $user = User::factory()
            ->withoutFacebookCredentials()
            ->withoutPasswordCredentials()
            ->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle/forget')
            ->assertForbidden();
    }

    public function testForgetGoogleAccount(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle/forget')
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'google_id' => null,
        ]);
    }
}
