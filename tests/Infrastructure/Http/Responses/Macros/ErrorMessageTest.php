<?php

namespace Tests\Infrastructure\Http\Responses\Macros;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Infrastructure\AbstractInfrastructureTestCase as TestCase;

final class ErrorMessageTest extends TestCase
{
    use WithFaker;

    public function testJsonResponseWithErrorMessageAnd_400StatusCode(): void
    {
        /** @var JsonResponse $response */
        $response = response()->errorMessage(
            $message = $this->faker->text()
        );

        $body = $response->getData();

        $this->assertObjectHasAttribute('errorMessage', $body);
        $this->assertEquals($message, $body->errorMessage);
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->status());
    }
}
