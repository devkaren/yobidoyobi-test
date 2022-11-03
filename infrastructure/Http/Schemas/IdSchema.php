<?php

namespace Infrastructure\Http\Schemas;

/**
 * @OA\Schema(schema="IdSchema", type="object")
 */
final class IdSchema extends AbstractSchema
{
    public function __construct(
        /** @OA\Property() */
        public int $id,
    ) {
        //
    }
}
