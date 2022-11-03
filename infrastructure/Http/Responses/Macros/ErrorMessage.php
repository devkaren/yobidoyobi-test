<?php

namespace Infrastructure\Http\Responses\Macros;

use Illuminate\Http\JsonResponse;
use Infrastructure\AbstractMacro;
use Infrastructure\Http\Schemas\ErrorMessageSchema;

final class ErrorMessage extends AbstractMacro
{
    protected function register(): void
    {
        response()->macro('errorMessage', function (string $message, ?int $code = null): JsonResponse {
            return response()->schema(new ErrorMessageSchema($message), $code ?? JsonResponse::HTTP_BAD_REQUEST);
        });
    }
}
