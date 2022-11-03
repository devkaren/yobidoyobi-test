<?php

namespace Modules\Backend\Order\Http\Actions;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Backend\Order\Services\BackendOrderQueryService;
use Infrastructure\Http\Resources\Backend\BackendOrderResource;
use Modules\Backend\Order\Http\Requests\ViewBackendOrderRequest;

final class ViewBackendOrderAction
{
    /**
     * @OA\Get(
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
     *              @OA\Property(property="data", ref="#/components/schemas/BackendOrderSchema"),
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
    public function __invoke(ViewBackendOrderRequest $request, BackendOrderQueryService $service): JsonResource
    {
        $dto = $request->toDto();
        $order = $service->view($dto);

        return new BackendOrderResource($order);
    }
}
