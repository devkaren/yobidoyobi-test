<?php

namespace Modules\Backend\Order\Dto;

final class CreateBackendOrderDto
{
    public function __construct(
        public readonly string $phone,
        public readonly string $full_name,
        public readonly string $address,
        public readonly int $amount,
    ) {
        //
    }
}
