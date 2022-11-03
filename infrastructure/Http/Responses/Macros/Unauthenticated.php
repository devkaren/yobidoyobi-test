<?php

namespace Infrastructure\Http\Responses\Macros;

use Illuminate\Http\JsonResponse;
use Infrastructure\AbstractMacro;

final class Unauthenticated extends AbstractMacro
{
    protected function register(): void
    {
        response()->macro('unauthenticated', function (string $message): JsonResponse {
            return response()->errorMessage($message, JsonResponse::HTTP_UNAUTHORIZED);
        });
    }
}
