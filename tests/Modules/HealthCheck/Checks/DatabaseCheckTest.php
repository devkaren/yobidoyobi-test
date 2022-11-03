<?php

namespace Tests\Modules\HealthCheck\Checks;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\HealthCheck\Checks\DatabaseCheck;
use Tests\Modules\AbstractModuleTestCase as TestCase;
use Modules\HealthCheck\Exceptions\CheckFailedException;

final class DatabaseCheckTest extends TestCase
{
    use WithFaker;

    public function testFail(): void
    {
        $e = new Exception($this->faker->uuid());
        $facade = DB::spy();

        $facade
            ->shouldReceive('connection')
            ->once()
            ->andReturnSelf();

        $facade
            ->shouldReceive('getPdo')
            ->once()
            ->andReturnUsing(static function () use ($e) {
                throw $e;
            });

        $check = new DatabaseCheck();

        $this->expectException(CheckFailedException::class);
        $this->expectExceptionMessage($e->getMessage());

        $check->check();
    }
}
