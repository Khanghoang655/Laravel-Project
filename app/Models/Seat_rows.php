<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat_rows extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function seats(){
        return $this->hasMany(Seat::class,'seat_row_id');
    }
    public function footballMatch()
    {
        return $this->belongsTo(FootballMatch::class, 'match_id')->withTrashed();
    }
}
