<?php

namespace Modules\Backend\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Backend\Order\Dto\CreateBackendOrderDto;

final class CreateBackendOrderRequest extends FormRequest
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

    public function toDto(): CreateBackendOrderDto
    {
        return new CreateBackendOrderDto(
            $this->input('phone'),
            $this->input('full_name'),
            $this->input('address'),
            $this->input('amount')
        );
    }
}
