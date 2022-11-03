<?php

namespace Modules\Backend\Order\Http\Actions;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Backend\Order\Services\BackendOrderQueryService;
use Infrastructure\Http\Resources\Backend\BackendOrderResource;
use Modules\Backend\Order\Http\Requests\QueryBackendOrderRequest;

final class QueryBackendOrderAction
{
    /**
     * @OA\Get(
     *      path="/backend/order",
     *      tags={"Backend - Order"},
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/BackendOrderSchema")
     *              ),
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
    public function __invoke(QueryBackendOrderRequest $request, BackendOrderQueryService $service): ResourceCollection
    {
        $dto = $request->toDto();
        $orders = $service->query($dto);

        return BackendOrderResource::collection($orders);
    }
}
