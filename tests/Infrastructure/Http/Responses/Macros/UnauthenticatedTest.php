<?php

namespace Tests\Infrastructure\Http\Responses\Macros;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Infrastructure\AbstractInfrastructureTestCase as TestCase;

final class UnauthenticatedTest extends TestCase
{
    use WithFaker;

    public function testJsonResponseNotFoundWithErrorMessageAnd_401StatusCode(): void
    {
        /** @var JsonResponse $response */
        $response = response()->unauthenticated(
            $message = $this->faker->text()
        );

        $body = $response->getData();

        $this->assertObjectHasAttribute('errorMessage', $body);
        $this->assertEquals($message, $body->errorMessage);
        $this->assertEquals(JsonResponse::HTTP_UNAUTHORIZED, $response->status());
    }
}
