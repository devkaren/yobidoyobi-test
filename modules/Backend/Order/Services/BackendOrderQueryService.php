<?php

namespace Modules\Backend\Order\Services;

use Infrastructure\Eloquent\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Modules\Backend\Order\Dto\ViewBackendOrderDto;
use Modules\Backend\Order\Dto\QueryBackendOrderDto;

final class BackendOrderQueryService
{
    public function query(QueryBackendOrderDto $request): Collection
    {
        return Order::query()->orderBy('id')->get();
    }

    public function view(ViewBackendOrderDto $request): Order
    {
        return Order::findOrFail($request->id);
    }
}
