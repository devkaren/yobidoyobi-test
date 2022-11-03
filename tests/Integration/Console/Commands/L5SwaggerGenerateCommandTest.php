<?php

namespace Tests\Integration\Console\Commands;

use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class L5SwaggerGenerateCommandTest extends TestCase
{
    public function testGenerateDocs(): void
    {
        $this
            ->artisan('l5-swagger:generate')
            ->assertSuccessful();
    }
}
