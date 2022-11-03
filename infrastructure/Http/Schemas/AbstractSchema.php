<?php

namespace Infrastructure\Http\Schemas;

use Illuminate\Contracts\Support\Arrayable;

abstract class AbstractSchema implements Arrayable
{
    final public function toArray(): array
    {
        return collect(get_object_vars($this))->toArray();
    }
}
