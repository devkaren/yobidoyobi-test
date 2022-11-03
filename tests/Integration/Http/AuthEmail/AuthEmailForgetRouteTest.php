<?php

namespace Tests\Integration\Http\AuthEmail;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthEmailForgetRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testUnauthenticated(): void
    {
        $this
            ->json('POST', 'authEmail/forget')
            ->assertUnauthorized();
    }

    public function testForgetNoEmail(): void
    {
        $user = User::factory()->withoutPasswordCredentials()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authEmail/forget')
            ->assertForbidden();
    }

    public function testForgetEmail(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authEmail/forget')
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => null,
            'email_verification_token' => null,
            'email_verified_at' => null,
        ]);
    }
}
