<?php

namespace Tests\Modules\AuthEmail\Mail;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\Modules\AbstractModuleTestCase as TestCase;
use Modules\AuthEmail\Mail\AuthEmailVerificationLinkMail;

final class AuthEmailVerificationLinkMailTest extends TestCase
{
    use WithFaker;

    public function testSeeEmailAndLink(): void
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();
        $url = $this->faker->url();

        $mail = new AuthEmailVerificationLinkMail(
            $firstName,
            $lastName,
            $url,
        );

        $mail->assertSeeInHtml($firstName);
        $mail->assertSeeInHtml($lastName);
        $mail->assertSeeInHtml($url);
    }
}
