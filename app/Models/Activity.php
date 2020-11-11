<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Timesheet;

class Activity extends Model
{

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'activities';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'desc',
    'start_time',
    'stop_time',
    'timesheet_id'
  ];

  /**
   * The model's default values for attributes.
   *
   * @var array
   */
  protected $attributes = [];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [];

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = [];

  /**
   * The "booted" method of the model
   * 
   * @return void
   */
  public static function booted()
  {
  }

  /**
   * Activity detail from timesheet
   * 
   * @return object
   */
  public function timesheet()
  {
    return $this->hasOne(Timesheet::class, 'id', 'timesheet_id');
  }
}
