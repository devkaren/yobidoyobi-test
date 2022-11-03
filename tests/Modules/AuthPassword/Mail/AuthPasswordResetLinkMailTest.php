<?php

namespace Tests\Modules\AuthPassword\Mail;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\Modules\AbstractModuleTestCase as TestCase;
use Modules\AuthPassword\Mail\AuthPasswordResetLinkMail;

final class AuthPasswordResetLinkMailTest extends TestCase
{
    use WithFaker;

    public function testSeeEmailAndLink(): void
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();
        $url = $this->faker->url();

        $mail = new AuthPasswordResetLinkMail(
            $firstName,
            $lastName,
            $url,
        );

        $mail->assertSeeInHtml($firstName);
        $mail->assertSeeInHtml($lastName);
        $mail->assertSeeInHtml($url);
    }
}
