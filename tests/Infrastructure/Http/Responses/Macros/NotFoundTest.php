<?php

namespace Tests\Infrastructure\Http\Responses\Macros;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Infrastructure\AbstractInfrastructureTestCase as TestCase;

final class NotFoundTest extends TestCase
{
    use WithFaker;

    public function testJsonResponseNotFoundWithErrorMessageAnd_404StatusCode(): void
    {
        /** @var JsonResponse $response */
        $response = response()->notFound(
            $message = $this->faker->text()
        );

        $body = $response->getData();

        $this->assertObjectHasAttribute('errorMessage', $body);
        $this->assertEquals($message, $body->errorMessage);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $response->status());
    }
}
