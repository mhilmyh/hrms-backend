<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Activity;

class Timesheet extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'timesheets';

  /**
   * Remove updated at
   * 
   * @var null
   */
  const UPDATED_AT = null;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'is_approved',
    'user_id',
  ];

  /**
   * The model's default values for attributes.
   *
   * @var array
   */
  protected $attributes = [
    'is_approved' => false,
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'is_approved' => 'boolean'
  ];

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
   * User owner of timesheet
   *
   * @return object
   */
  public function user()
  {
    return $this->hasOne(User::class, 'id', 'user_id');
  }

  /**
   * Activities for timesheets
   *
   * @return object
   */
  public function activities()
  {
    return $this->hasMany(Activity::class, 'timesheet_id', 'id');
  }

  /**
   * Delete model with relation
   * 
   * @return bool
   */
  public function delete()
  {
    DB::beginTransaction();

    $this->activities()->delete();

    $result = parent::delete();

    DB::commit();

    return $result;
  }
}
