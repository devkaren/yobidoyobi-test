<?php

namespace Tests\Modules\HealthCheck\Checks;

use Exception;
use Hamcrest\Matchers;
use Illuminate\Support\Facades\Log;
use Modules\HealthCheck\Checks\LogCheck;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Modules\AbstractModuleTestCase as TestCase;
use Modules\HealthCheck\Exceptions\CheckFailedException;

final class LogCheckTest extends TestCase
{
    use WithFaker;

    public function testFail(): void
    {
        $e = new Exception($this->faker->uuid());

        Log::spy()
            ->shouldReceive('info')
            ->withArgs([Matchers::stringValue()])
            ->once()
            ->andReturnUsing(static function () use ($e) {
                throw $e;
            });

        $check = new LogCheck();

        $this->expectException(CheckFailedException::class);
        $this->expectExceptionMessage($e->getMessage());

        $check->check();
    }
}
