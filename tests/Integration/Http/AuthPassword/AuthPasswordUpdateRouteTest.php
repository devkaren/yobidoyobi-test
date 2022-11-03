<?php

namespace Tests\Integration\Http\AuthPassword;

use Illuminate\Support\Facades\Hash;
use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthPasswordUpdateRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testUnauthenticated(): void
    {
        $this
            ->json('POST', 'authPassword/update', [
                'password' => $this->faker->password(8),
            ])
            ->assertUnauthorized();
    }

    public function testMissingCurrentPassword(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authPassword/update', [
                'password' => 'Password123~',
            ])
            ->assertUnprocessable();
    }

    public function testSetPassword(): void
    {
        $user = User::factory()->withoutPasswordCredentials()->create();
        $now = $this->fakeCurrentTimestamp();

        $this
            ->actingAs($user)
            ->json('POST', 'authPassword/update', $request = [
                'currentPassword' => null,
                'password' => 'Password123~',
            ])
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'password_changed_at' => $now,
        ]);

        $user->refresh();

        $this->assertNotEmpty($user->password);
        $this->assertTrue(Hash::check($request['password'], $user->password));
    }

    public function testUpdatePassword(): void
    {
        $user = User::factory()->create();
        $now = $this->fakeCurrentTimestamp();

        $this
            ->actingAs($user)
            ->json('POST', 'authPassword/update', $request = [
                'currentPassword' => 'password',
                'password' => 'Password123~',
            ])
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'password_changed_at' => $now,
        ]);

        $user->refresh();

        $this->assertNotEmpty($user->password);
        $this->assertTrue(Hash::check($request['password'], $user->password));
    }
}
