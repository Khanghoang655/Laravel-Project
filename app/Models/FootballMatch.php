<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FootballMatch extends Model
{
  use HasFactory;
  use SoftDeletes;

  protected $guarded = [];
  public function competitions()
  {
    return $this->belongsTo(Competition::class, 'competition_id')->withTrashed();
  }
  public function seats()
  {
    return $this->hasMany(Seat::class, 'match_id')->withTrashed();
  }
  public function seat_rows()
  {
    return $this->hasMany(Seat_rows::class, 'match_id');
  }
  public function orderItem()
  {
    return $this->belongsTo(OrderItem::class, 'match_id')->withTrashed();
  }

}
