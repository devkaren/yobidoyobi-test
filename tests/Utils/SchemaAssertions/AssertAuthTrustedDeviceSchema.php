<?php

namespace Tests\Utils\SchemaAssertions;

use Closure;
use Illuminate\Testing\TestResponse;
use Infrastructure\Eloquent\Models\UserTrustedDevice;

final class AssertAuthTrustedDeviceSchema extends AssertSchema
{
    public function assert(): Closure
    {
        return function (UserTrustedDevice $device, string $container = 'data'): TestResponse {
            return $this->response
                ->assertJsonPath("{$container}.id", $device->id)
                ->assertJsonPath("{$container}.ip", $device->ip)
                ->assertJsonPath("{$container}.userAgent", $device->user_agent)
                ->assertJsonPath("{$container}.validTo", $device->valid_to->toRfc3339String());
        };
    }
}
