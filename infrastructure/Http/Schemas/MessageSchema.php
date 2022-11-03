<?php

namespace Infrastructure\Http\Schemas;

/**
 * @OA\Schema(schema="MessageSchema", type="object")
 */
final class MessageSchema extends AbstractSchema
{
    public function __construct(
        /** @OA\Property() */
        public string $message,
    ) {
        //
    }
}
