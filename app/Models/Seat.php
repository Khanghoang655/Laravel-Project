<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seat extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function footballMatch()
    {
        return $this->belongsTo(FootballMatch::class, 'match_id')->withTrashed();
    }
    public function seat_row()
    {
        return $this->belongsTo(Seat_rows::class, 'seat_row_id');
    }
}
