<?php

namespace Tests\Utils\SchemaAssertions;

use Closure;
use Illuminate\Testing\Assert;
use Illuminate\Support\Collection;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\ExpectationFailedException;

abstract class AssertSchema
{
    final public function __construct(
        protected TestResponse $response,
    ) {
        //
    }

    final public static function bind(): void
    {
        $static = static::class;
        $macro = lcfirst(class_basename($static));

        TestResponse::macro($macro, fn (...$args) => (new $static($this))->assert()(...$args)); // phpcs:ignore
        TestResponse::macro("{$macro}Collection", fn (...$args) => (new $static($this))->assertCollection()(...$args)); // phpcs:ignore
    }

    abstract public function assert(): Closure;

    final public function assertCollection(): Closure
    {
        $matcher = $this->assert();
        $messageFactory = $this->createComparisonFailureMessage(...);

        return function (Collection $collection, string $container = 'data') use ($matcher, $messageFactory): TestResponse {
            foreach ($collection as $key => $value) {
                try {
                    $matcher($value, "{$container}.{$key}");
                } catch (ExpectationFailedException $e) {
                    Assert::assertTrue(false, $messageFactory($e, $value, "{$container}.{$key}"));
                }
            }

            return $this->response;
        };
    }

    private function createComparisonFailureMessage(ExpectationFailedException $e, mixed $value, string $container): string
    {
        $message = $e->getComparisonFailure()?->getMessage() ?? $e->getMessage();

        return $message
            . "\n"
            . "Expected json path: `{$container}`\n"
            . "Expected entry: \n" . collect($value)->toJson(JSON_PRETTY_PRINT);
    }
}
