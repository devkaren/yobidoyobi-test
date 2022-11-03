<?php

namespace Modules\Backend\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Backend\Order\Dto\DeleteBackendOrderDto;

final class DeleteBackendOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDto(): DeleteBackendOrderDto
    {
        return new DeleteBackendOrderDto(
            $this->route('id'),
        );
    }
}
