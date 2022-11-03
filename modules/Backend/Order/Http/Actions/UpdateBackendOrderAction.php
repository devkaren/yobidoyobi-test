<?php

namespace Modules\Backend\Order\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Backend\Order\Services\BackendOrderCommandService;
use Modules\Backend\Order\Http\Requests\UpdateBackendOrderRequest;

final class UpdateBackendOrderAction
{
    /**
     * @OA\Put(
     *      path="/backend/order/{id}",
     *      tags={"Backend - Order"},
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          parameter="id",
     *          @OA\Schema(type="integer"),
     *          in="path",
     *          required=true,
     *      ),
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
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/MessageSchema",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          @OA\JsonContent(
     *              type="object",
     *              ref="#/components/schemas/ErrorMessageSchema",
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
    public function __invoke(UpdateBackendOrderRequest $request, BackendOrderCommandService $service): JsonResponse
    {
        $dto = $request->toDto();
        $service->update($dto);

        return response()->message('Order has been successfully updated');
    }
}
