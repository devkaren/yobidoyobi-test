<?php

namespace Infrastructure\Http\Resources\Traits;

use Illuminate\Support\Collection;
use Infrastructure\Http\Schemas\AbstractSchema;

trait ConvertsSchemaToArray
{
    abstract public function toSchema($request): AbstractSchema;

    public static function schema($resource): ?AbstractSchema
    {
        return $resource ? (new static($resource))->toSchema(request()) : null;
    }

    public static function schemas(array | Collection $collection): ?array
    {
        return collect($collection)->map(static fn ($item) => self::schema($item))->toArray();
    }

    final public function toArray($request): array
    {
        return $this
            ->toSchema($request)
            ->toArray();
    }
}
