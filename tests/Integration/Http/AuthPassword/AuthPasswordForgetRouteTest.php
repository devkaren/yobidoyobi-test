<?php

namespace Tests\Integration\Http\AuthPassword;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthPasswordForgetRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testUnauthenticated(): void
    {
        $this
            ->json('POST', 'authPassword/forget', [
                'currentPassword' => $this->faker->password(8),
            ])
            ->assertUnauthorized();
    }

    public function testForgetWithNoPassword(): void
    {
        $user = User::factory()->withoutPasswordCredentials()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authPassword/forget', [
                'currentPassword' => null,
            ])
            ->assertForbidden();
    }

    public function testForgetWithInvalidOldPassword(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authPassword/forget', [
                'currentPassword' => $this->faker->uuid(),
            ])
            ->assertUnprocessable();
    }

    public function testForgetPassword(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authPassword/forget', [
                'currentPassword' => 'password',
            ])
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'password' => null,
            'password_changed_at' => null,
        ]);
    }
}
