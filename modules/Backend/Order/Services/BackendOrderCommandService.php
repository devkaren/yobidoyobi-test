<?php

namespace Modules\Backend\Order\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Infrastructure\Eloquent\Models\Order;
use Modules\Backend\Order\Dto\CreateBackendOrderDto;
use Modules\Backend\Order\Dto\DeleteBackendOrderDto;
use Modules\Backend\Order\Dto\UpdateBackendOrderDto;

final class BackendOrderCommandService
{
    public function create(CreateBackendOrderDto $request): int
    {
        return DB::transaction(static function () use ($request): int {
            $order = Order::create([
                'phone' => $request->phone,
                'full_name' => $request->full_name,
                'address' => $request->address,
                'amount' => $request->amount,
            ]);

            Event::dispatch('backend.order.created', [$order->id]);

            return $order->id;
        });
    }

    public function update(UpdateBackendOrderDto $request): void
    {
        DB::transaction(static function () use ($request): void {
            $order = Order::findOrFail($request->id);

            $order->update([
                'phone' => $request->phone,
                'full_name' => $request->full_name,
                'address' => $request->address,
                'amount' => $request->amount,
            ]);

            Event::dispatch('backend.order.updated', [$order->id]);
        });
    }

    public function delete(DeleteBackendOrderDto $request): void
    {
        DB::transaction(static function () use ($request): void {
            $order = Order::findOrFail($request->id);

            $order->delete();

            Event::dispatch('backend.order.deleted', [$order->id]);
        });
    }
}
