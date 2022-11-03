<?php

namespace Tests\Integration\Database\Seeders;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\Integration\AbstractIntegrationTestCase as TestCase;

final class DatabaseSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function testSeedDoNothing(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertTrue(true);
    }
}
