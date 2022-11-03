<?php

namespace Infrastructure\Http\Responses\Macros;

use Illuminate\Http\JsonResponse;
use Infrastructure\AbstractMacro;
use Infrastructure\Http\Schemas\ErrorBagSchema;

final class Errors extends AbstractMacro
{
    protected function register(): void
    {
        response()->macro('errors', function (array $errors, ?int $code = null): JsonResponse {
            return response()->schema(new ErrorBagSchema($errors), $code ?? JsonResponse::HTTP_BAD_REQUEST);
        });
    }
}
