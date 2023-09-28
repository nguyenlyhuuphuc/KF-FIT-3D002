<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_SHPPING = 'shipping';
    const STATUS_CANCEL = 'cancel';
    const STATUS_REFUND = 'refund';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    protected $table = 'orders';

    public function order_items(){
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    
    public function order_payment_methods(){
        return $this->hasMany(OrderPaymentMethod::class, 'order_id');
    }
}
