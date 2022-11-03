<?php

namespace Infrastructure\Http\Responses\Macros;

use Illuminate\Http\JsonResponse;
use Infrastructure\AbstractMacro;

final class Forbidden extends AbstractMacro
{
    protected function register(): void
    {
        response()->macro('forbidden', function (string $message): JsonResponse {
            return response()->errorMessage($message, JsonResponse::HTTP_FORBIDDEN);
        });
    }
}
