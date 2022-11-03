<?php

namespace Modules\Backend\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Backend\Order\Dto\QueryBackendOrderDto;

final class QueryBackendOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDto(): QueryBackendOrderDto
    {
        return new QueryBackendOrderDto(
            //
        );
    }
}
