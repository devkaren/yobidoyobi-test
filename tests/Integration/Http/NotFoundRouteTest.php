<?php

namespace Tests\Integration\Http;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class NotFoundRouteTest extends TestCase
{
    use WithFaker;

    public function testRouteNotFoundResponse(): void
    {
        $this
            ->json('GET', $this->faker->sha256())
            ->assertNotFound();
    }
}
