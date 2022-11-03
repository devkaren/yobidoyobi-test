<?php

namespace Modules\Backend\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Backend\Order\Dto\ViewBackendOrderDto;

final class ViewBackendOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDto(): ViewBackendOrderDto
    {
        return new ViewBackendOrderDto(
            $this->route('id'),
        );
    }
}
