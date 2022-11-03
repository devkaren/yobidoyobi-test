<?php

namespace Tests\Integration\Http\AuthGoogle2FA;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthGoogle2FAIssueRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testUnauthenticated(): void
    {
        $this
            ->json('POST', 'authGoogle2FA/issue')
            ->assertUnauthorized();
    }

    public function testIssueAlreadyHavingGoogle_2faSecret(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle2FA/issue')
            ->assertForbidden();
    }

    public function testIssueWithoutEmail(): void
    {
        $user = User::factory()
            ->withoutGoogle2FA()
            ->withoutPasswordCredentials()
            ->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle2FA/issue')
            ->assertForbidden();
    }

    public function testIssueCreateSecretKeyAndReturnGeneratedQrImage(): void
    {
        $user = User::factory()->withoutGoogle2FA()->create();

        $this->expectGoogle2FASecretKeyGenerated($secretKey = $this->faker->sha1());
        $this->expectGoogle2FARecoveryCodeGenerated($recoveryCode = $this->faker->sha1());

        $this->expectGoogle2FAQrCodeRetrieved(
            $user->email,
            $secretKey,
            $qr = $this->faker->sha256(),
        );

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle2FA/issue')
            ->assertOk()
            ->assertAuthGoogle2FACredentialsSchema(compact('qr', 'secretKey', 'recoveryCode'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'google_2fa_secret' => $secretKey,
            'google_2fa_recovery_code' => $recoveryCode,
            'google_2fa_enabled' => false,
        ]);
    }
}
