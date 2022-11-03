<?php

namespace Tests\Integration\Http\AuthGoogle2FA;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class AuthGoogle2FAReissueRouteTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    public function testUnauthenticated(): void
    {
        $this
            ->json('POST', 'authGoogle2FA/reissue')
            ->assertUnauthorized();
    }

    public function testReissueNotHavingGoogle_2faSecret(): void
    {
        $user = User::factory()->withoutGoogle2FA()->create();

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle2FA/reissue')
            ->assertForbidden();
    }

    public function testReissue(): void
    {
        $user = User::factory()->create();

        $this->expectGoogle2FAQrCodeRetrieved(
            $user->email,
            $user->google_2fa_secret,
            $qr = $this->faker->sha256(),
        );

        $this
            ->actingAs($user)
            ->json('POST', 'authGoogle2FA/reissue')
            ->assertOk()
            ->assertAuthGoogle2FACredentialsSchema([
                'qr' => $qr,
                'secretKey' => $user->google_2fa_secret,
                'recoveryCode' => $user->google_2fa_recovery_code,
            ]);
    }
}
