<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPaymentMethod extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'order_payment_method';

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }
}
