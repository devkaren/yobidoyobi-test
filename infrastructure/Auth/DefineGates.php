<?php

namespace Infrastructure\Auth;

use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Facades\Gate;

trait DefineGates
{
    protected function defineGates(): void
    {
        foreach ($this->gates as $prefix => $policy) {
            $ref = new ReflectionClass($policy);

            foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $callback = [$policy, $method->getName()];

                Gate::define("{$prefix}@{$callback[1]}", $callback);
            }
        }
    }
}
