<?php

namespace Tests\Integration\Http\AuthPassword;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Modules\AuthPassword\Mail\AuthPasswordResetLinkMail;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthPasswordSendResetLinkRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testNotExistingEmail(): void
    {
        $this
            ->json('POST', 'authPassword/sendResetLink', [
                'email' => $this->faker->email(),
            ])
            ->assertStatus(400);
    }

    public function testSendThrottledResetLink(): void
    {
        $user = User::factory()->create();
        Password::createToken($user);

        $this
            ->json('POST', 'authPassword/sendResetLink', [
                'email' => $user->email,
            ])
            ->assertStatus(400);
    }

    public function testSendResetLink(): void
    {
        $user = User::factory()->create();

        $this
            ->json('POST', 'authPassword/sendResetLink', [
                'email' => $user->email,
            ])
            ->assertOk();

        Mail::assertSent(static fn (AuthPasswordResetLinkMail $m) => (
            $m->hasTo($user->email)
            && $m->firstName === $user->first_name
            && $m->lastName === $user->last_name
        ));
    }
}
