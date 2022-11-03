<?php

namespace Tests\Integration\Http\AuthEmail;

use Illuminate\Support\Facades\Mail;
use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Modules\AuthEmail\Mail\AuthEmailVerificationLinkMail;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthEmailSendVerificationLinkRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testUnauthenticated(): void
    {
        $this
            ->json('POST', 'authEmail/sendVerificationLink')
            ->assertUnauthorized();
    }

    public function testSendVerificationToAlreadyVerifiedEmail(): void
    {
        $user = User::factory()->emailVerified()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authEmail/sendVerificationLink')
            ->assertForbidden();
    }

    public function testSendVerificationToUserWithoutCredentials(): void
    {
        $user = User::factory()->withoutPasswordCredentials()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authEmail/sendVerificationLink')
            ->assertForbidden();
    }

    public function testResendVerificationLinkAndClearPreviousVerificationToken(): void
    {
        $user = User::factory()->emailVerified(false)->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authEmail/sendVerificationLink')
            ->assertOk();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email_verification_token' => $user->email_verification_token,
        ]);

        $user->refresh();
        $this->assertNotNull($user->email_verification_token);

        Mail::assertSent(static fn (AuthEmailVerificationLinkMail $m) => (
            $m->hasTo($user->email)
            && $m->firstName === $user->first_name
            && $m->lastName === $user->last_name
            && $m->url === 'http://client.app/verifyEmail?' . http_build_query([
                'email' => $user->email,
                'token' => $user->email_verification_token,
            ])
        ));
    }
}
