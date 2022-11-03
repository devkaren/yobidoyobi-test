<?php

namespace Infrastructure\Http\Responses\Macros;

use Illuminate\Http\JsonResponse;
use Infrastructure\AbstractMacro;

final class NotFound extends AbstractMacro
{
    protected function register(): void
    {
        response()->macro('notFound', function (string $message): JsonResponse {
            return response()->errorMessage($message, JsonResponse::HTTP_NOT_FOUND);
        });
    }
}
