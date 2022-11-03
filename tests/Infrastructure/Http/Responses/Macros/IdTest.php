<?php

namespace Tests\Infrastructure\Http\Responses\Macros;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Infrastructure\AbstractInfrastructureTestCase as TestCase;

final class IdTest extends TestCase
{
    use WithFaker;

    public function testJsonResponseWithIdAnd_200StatusCode(): void
    {
        /** @var JsonResponse $response */
        $response = response()->id(
            $id = $this->faker->randomDigitNotNull()
        );

        $body = $response->getData();

        $this->assertObjectHasAttribute('id', $body);
        $this->assertEquals($id, $body->id);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->status());
    }
}
