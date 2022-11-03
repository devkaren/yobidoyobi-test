<?php

namespace Modules\Backend\Order\Dto;

final class DeleteBackendOrderDto
{
    public function __construct(
        public readonly int $id,
    ) {
        //
    }
}
