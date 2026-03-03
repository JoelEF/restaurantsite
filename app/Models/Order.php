<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'customer_name', 'customer_email', 'customer_phone',
        'type', 'delivery_address', 'subtotal', 'delivery_fee', 'total',
        'status', 'payment_status', 'notes', 'printed_at',
    ];

    protected $casts = [
        'subtotal'    => 'decimal:2',
        'delivery_fee'=> 'decimal:2',
        'total'       => 'decimal:2',
        'printed_at'  => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber(): string
    {
        return 'IST-' . strtoupper(substr(uniqid(), -6));
    }
}
