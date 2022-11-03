<?php

namespace Infrastructure\Http\Schemas\Backend;

use Infrastructure\Http\Schemas\AbstractSchema;

/**
 * @OA\Schema(schema="BackendOrderSchema", type="object")
 */
final class BackendOrderSchema extends AbstractSchema
{
    public function __construct(
        /** @OA\Property() */
        public int $id,
        /** @OA\Property() */
        public string $phone,
        /** @OA\Property() */
        public string $full_name,
        /** @OA\Property() */
        public string $address,
        /** @OA\Property() */
        public int $amount,
        /** @OA\Property() */
        public ?string $created_at,
    ) {
        //
    }
}
