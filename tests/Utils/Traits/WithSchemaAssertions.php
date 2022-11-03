<?php

namespace Tests\Utils\Traits;

use Tests\Utils\SchemaAssertions\AssertAuthProfileSchema;
use Tests\Utils\SchemaAssertions\AssertAuthTrustedDeviceSchema;
use Tests\Utils\SchemaAssertions\AssertAuthGoogle2FACredentialsSchema;

trait WithSchemaAssertions
{
    private array $schemaAssertions = [
        AssertAuthProfileSchema::class,
        AssertAuthTrustedDeviceSchema::class,
        AssertAuthGoogle2FACredentialsSchema::class,
    ];

    private function setUpSchemaAssertions(): void
    {
        foreach ($this->schemaAssertions as $handler) {
            $handler::bind();
        }
    }

    private function tearDownSchemaAssertions(): void
    {
        //
    }
}
