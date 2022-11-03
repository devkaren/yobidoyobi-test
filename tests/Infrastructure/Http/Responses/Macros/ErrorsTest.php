<?php

namespace Tests\Infrastructure\Http\Responses\Macros;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Infrastructure\AbstractInfrastructureTestCase as TestCase;

final class ErrorsTest extends TestCase
{
    use WithFaker;

    public function testJsonResponseWithErrorsAnd_400StatusCode(): void
    {
        /** @var JsonResponse $response */
        $response = response()->errors(
            $errors = [$this->faker->slug() => $this->faker->text()]
        );

        $body = $response->getData();

        $this->assertObjectHasAttribute('errors', $body);
        $this->assertEqualsCanonicalizing((object) $errors, $body->errors);
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->status());
    }
}
