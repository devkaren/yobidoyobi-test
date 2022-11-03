<?php

namespace Infrastructure\Http\Schemas;

/**
 * @OA\Schema(schema="ErrorBagSchema", type="object")
 */
final class ErrorBagSchema extends AbstractSchema
{
    public function __construct(
        /** @OA\Property(type="object", additionalProperties={"type":"array","items":{"type":"string"}}) */
        public array $errors,
    ) {
        //
    }
}
