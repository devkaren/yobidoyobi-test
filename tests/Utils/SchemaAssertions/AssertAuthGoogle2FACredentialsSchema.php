<?php

namespace Tests\Utils\SchemaAssertions;

use Closure;
use Illuminate\Testing\TestResponse;

final class AssertAuthGoogle2FACredentialsSchema extends AssertSchema
{
    public function assert(): Closure
    {
        return function (mixed $credentials, string $container = 'data'): TestResponse {
            return $this->response
                ->assertJsonPath("{$container}.qr", data_get($credentials, 'qr'))
                ->assertJsonPath("{$container}.secretKey", data_get($credentials, 'secretKey'))
                ->assertJsonPath("{$container}.recoveryCode", data_get($credentials, 'recoveryCode'));
        };
    }
}
