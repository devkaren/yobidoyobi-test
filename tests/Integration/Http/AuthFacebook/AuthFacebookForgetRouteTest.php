<?php

namespace Tests\Integration\Http\AuthFacebook;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthFacebookForgetRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testUnauthenticated(): void
    {
        $this
            ->json('POST', 'authFacebook/forget')
            ->assertUnauthorized();
    }

    public function testForgetForUserWithoutFacebookLinked(): void
    {
        $user = User::factory()->withoutFacebookCredentials()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authFacebook/forget')
            ->assertForbidden();
    }

    public function testForgetForUserWithoutAnyOtherCredentialsLinked(): void
    {
        $user = User::factory()
            ->withoutGoogleCredentials()
            ->withoutPasswordCredentials()
            ->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authFacebook/forget')
            ->assertForbidden();
    }

    public function testForgetFacebookAccount(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authFacebook/forget')
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'facebook_id' => null,
        ]);
    }
}
