<?php

namespace Infrastructure\Http\Responses\Macros;

use Illuminate\Http\JsonResponse;
use Infrastructure\AbstractMacro;
use Infrastructure\Http\Schemas\AbstractSchema;

final class Schema extends AbstractMacro
{
    protected function register(): void
    {
        response()->macro('schema', function (AbstractSchema $schema, ?int $code = null): JsonResponse {
            return response()->json($schema->toArray(), $code ?? JsonResponse::HTTP_OK);
        });
    }
}
