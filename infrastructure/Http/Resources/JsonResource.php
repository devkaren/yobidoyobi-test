<?php

namespace Infrastructure\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;

class JsonResource extends BaseJsonResource
{
    public static function collection($resource)
    {
        return new AnonymousResourceCollection($resource, static::class);
    }
}
