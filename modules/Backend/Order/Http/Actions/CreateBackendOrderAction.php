<?php

namespace Modules\Backend\Order\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Backend\Order\Services\BackendOrderCommandService;
use Modules\Backend\Order\Http\Requests\CreateBackendOrderRequest;

final class CreateBackendOrderAction
{
    /**
     * @OA\Post(
     *      path="/backend/order",
     *      tags={"Backend - Order"},
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="phone", type="string"),
     *              @OA\Property(property="full_name", type="string"),
     *              @OA\Property(property="address", type="string"),
     *              @OA\Property(property="amount", type="number"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/IdSchema",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ErrorMessageSchema",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ErrorBagSchema",
     *          ),
     *      ),
     * )
     */
    public function __invoke(CreateBackendOrderRequest $request, BackendOrderCommandService $service): JsonResponse
    {
        $dto = $request->toDto();
        $id = $service->create($dto);

        return response()->id($id, JsonResponse::HTTP_CREATED);
    }
}
