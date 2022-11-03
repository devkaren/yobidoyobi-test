<?php

namespace Modules\Backend\Order\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Backend\Order\Services\BackendOrderCommandService;
use Modules\Backend\Order\Http\Requests\DeleteBackendOrderRequest;

final class DeleteBackendOrderAction
{
    /**
     * @OA\Delete(
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
     * )
     */
    public function __invoke(DeleteBackendOrderRequest $request, BackendOrderCommandService $service): JsonResponse
    {
        $dto = $request->toDto();
        $service->delete($dto);

        return response()->message('Orders has been successfully deleted');
    }
}
