<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'order_item';

    public function footballMatch(){
        return $this->belongsTo(FootballMatch::class,'match_id');
    }
    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }
}
