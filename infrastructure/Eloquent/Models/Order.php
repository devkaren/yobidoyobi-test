<?php

namespace Infrastructure\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'phone',
        'full_name',
        'address',
        'amount'
    ];

    /**
     * Custom-Attribute for printing the amount as human-readable string
     * @return string|null
     */
    public function getHumanAmountAttribute() : ?string
    {
        // Network traffic speed is provided as MBit per second
        return number_format($this->amount, 2, ',', '.');
    }
}
