<?php

namespace Infrastructure\Http\Schemas;

/**
 * @OA\Schema(schema="ErrorMessageSchema", type="object")
 */
final class ErrorMessageSchema extends AbstractSchema
{
    public function __construct(
        /** @OA\Property() */
        public string $errorMessage,
    ) {
        //
    }
}
