<?php

namespace Tests\Integration\Http\AuthPassword;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthPasswordResetRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testResetPasswordWithInvalidEmail(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $this
            ->json('POST', 'authPassword/reset', [
                'email' => $this->faker->unique()->safeEmail(),
                'token' => $token,
                'password' => 'Password123~',
            ])
            ->assertStatus(400);
    }

    public function testResetPasswordWithInvalidToken(): void
    {
        $user = User::factory()->create();

        $this
            ->json('POST', 'authPassword/reset', [
                'email' => $user->email,
                'token' => $this->faker->sha1(),
                'password' => 'Password123~',
            ])
            ->assertStatus(400);
    }

    public function testResetPassword(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);
        $now = $this->fakeCurrentTimestamp();

        $this
            ->json('POST', 'authPassword/reset', $request = [
                'email' => $user->email,
                'token' => $token,
                'password' => 'Password123~',
            ])
            ->assertOk();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'password_changed_at' => $now,
        ]);

        $user->refresh();

        $this->assertTrue(Hash::check($request['password'], $user->password));
        $this->assertDatabaseCount('password_resets', 0);
    }
}
