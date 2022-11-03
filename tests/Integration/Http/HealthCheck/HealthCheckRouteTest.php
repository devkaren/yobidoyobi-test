<?php

namespace Tests\Integration\Http\HealthCheck;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class HealthCheckRouteTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function testHealthCheck(): void
    {
        $this
            ->json('GET', 'healthCheck')
            ->assertOk()
            ->assertJsonPath('message', 'Healthy!');
    }
}
