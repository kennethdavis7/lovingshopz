<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id',
        'payment_type',
        'status',
        'transaction_time',
        'snap_token',
        'is_finished'
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public function orderItem()
    {
        return $this->hasMany(OrderItem::class, "order_id", "id");
    }
}
