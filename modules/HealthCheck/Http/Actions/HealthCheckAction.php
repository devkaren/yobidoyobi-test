<?php

namespace Modules\HealthCheck\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\HealthCheck\Services\HealthCheckService;

final class HealthCheckAction
{
    /**
     * @OA\Get(
     *      path="/healthCheck",
     *      tags={"HealthCheck"},
     *      description="Check application health",
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/MessageSchema",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Unhealthy",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ErrorMessageSchema",
     *          ),
     *      ),
     * )
     */
    public function __invoke(HealthCheckService $service): JsonResponse
    {
        $service->healthCheck();

        return response()->message(trans('messages.http.healthy'));
    }
}
