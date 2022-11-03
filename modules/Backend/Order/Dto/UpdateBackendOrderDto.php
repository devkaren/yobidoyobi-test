<?php

namespace Modules\Backend\Order\Dto;

final class UpdateBackendOrderDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $phone,
        public readonly string $full_name,
        public readonly string $address,
        public readonly int $amount,
    ) {
        //
    }
}
