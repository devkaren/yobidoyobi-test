<?php

namespace Infrastructure\Http\Resources\Backend;

use Infrastructure\Eloquent\Models\Order;
use Infrastructure\Http\Resources\JsonResource;
use Infrastructure\Http\Resources\Traits\ConvertsSchemaToArray;
use Infrastructure\Http\Schemas\Backend\BackendOrderSchema;

/**
 * @property Order $resource
 */
final class BackendOrderResource extends JsonResource
{
    use ConvertsSchemaToArray;

    public function toSchema($request): BackendOrderSchema
    {
        return new BackendOrderSchema(
            $this->resource->id,
            $this->resource->phone,
            $this->resource->full_name,
            $this->resource->address,
            $this->resource->amount,
            $this->resource->created_at,
        );
    }
}
