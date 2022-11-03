<?php

namespace Tests\Infrastructure\Http\Responses\Macros;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Infrastructure\AbstractInfrastructureTestCase as TestCase;

final class MessageTest extends TestCase
{
    use WithFaker;

    public function testJsonResponseWithMessageAnd_200StatusCode(): void
    {
        /** @var JsonResponse $response */
        $response = response()->message(
            $message = $this->faker->text()
        );

        $body = $response->getData();

        $this->assertObjectHasAttribute('message', $body);
        $this->assertEquals($message, $body->message);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->status());
    }
}
