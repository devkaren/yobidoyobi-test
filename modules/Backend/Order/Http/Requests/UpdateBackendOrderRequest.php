<?php

namespace Modules\Backend\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Backend\Order\Dto\UpdateBackendOrderDto;

final class UpdateBackendOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone' => [
                'required',
                'string',
            ],
            'full_name' => [
                'required',
                'string',
            ],
            'address' => [
                'required',
                'string',
            ],
            'amount' => [
                'required',
                'int',
            ],
        ];
    }

    public function toDto(): UpdateBackendOrderDto
    {
        return new UpdateBackendOrderDto(
            $this->route('id'),
            $this->input('phone'),
            $this->input('full_name'),
            $this->input('address'),
            $this->input('amount')
        );
    }
}
