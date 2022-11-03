<?php

namespace Infrastructure\Http\Responses\Macros;

use Illuminate\Http\JsonResponse;
use Infrastructure\AbstractMacro;
use Infrastructure\Http\Schemas\MessageSchema;

final class Message extends AbstractMacro
{
    protected function register(): void
    {
        response()->macro('message', function (string $message, ?int $code = null): JsonResponse {
            return response()->schema(new MessageSchema($message), $code ?? JsonResponse::HTTP_OK);
        });
    }
}
