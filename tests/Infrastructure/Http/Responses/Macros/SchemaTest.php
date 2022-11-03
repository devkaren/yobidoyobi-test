<?php

namespace Tests\Infrastructure\Http\Responses\Macros;

use Faker\Generator;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Infrastructure\Http\Schemas\AbstractSchema;
use Tests\Infrastructure\AbstractInfrastructureTestCase as TestCase;

final class SchemaTest extends TestCase
{
    use WithFaker;

    public function testJsonResponseWithSchemaAnd_200StatusCode(): void
    {
        /** @var JsonResponse $response */
        $response = response()->schema(
            $schema = new class($this->faker) extends AbstractSchema {
                public string $text;

                public function __construct(Generator $faker)
                {
                    $this->text = $faker->text();
                }
            }
        );

        $body = $response->getData();

        $this->assertEqualsCanonicalizing((object) $schema->toArray(), $body);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->status());
    }
}
