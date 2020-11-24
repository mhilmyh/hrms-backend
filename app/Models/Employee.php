<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

use App\Models\User;

class Employee extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'employees';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'first_name',
    'mid_name',
    'last_name',
    'phone',
    'gender',
    'birthday',
    'salary',
    'job_position',
    'rating',
    'user_id',
    'image_id',
    'address_id',
    'supervisor_id',
    'department_id',
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
  protected $casts = [
    'rating' => 'integer',
    'salary' => 'integer',
  ];

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = [];

  /**
   * Append new value to response
   * 
   * @var array
   */
  protected $appends = ['full_name'];


  /**
   * The "booted" method of the model
   *
   * @return void
   */
  public static function booted()
  {
  }

  /**
   * Computed property eloquent
   * 
   * @return string
   */
  public function getFullNameAttribute()
  {
    if (empty($this->mid_name) && !empty($this->last_name))
      return $this->first_name . ' ' . $this->last_name;
    else if (empty($this->mid_name) && empty($this->last_name))
      return $this->first_name;
    return $this->first_name . ' ' . $this->mid_name . ' ' . $this->last_name;
  }


  /**
   * User of employee
   *
   * @return object
   */
  public function user()
  {
    return $this->belongsTo(User::class, 'id', 'user_id');
  }

  /**
   * Image of employee
   *
   * @return object
   */
  public function image()
  {
    return $this->hasOne(Image::class, 'id', 'image_id');
  }

  /**
   * Address of employee
   *
   * @return object
   */
  public function address()
  {
    return $this->hasOne(Address::class, 'id', 'address_id');
  }

  /**
   * Department of employee
   *
   * @return object
   */
  public function department()
  {
    return $this->hasOne(Department::class, 'id', 'department_id');
  }

  /**
   * Supervisor of employee
   *
   * @return object
   */
  public function supervisor()
  {
    return $this->hasOne(User::class, 'id', 'supervisor_id');
  }

  /**
   * Delete model with relation
   * 
   * @return bool
   */
  public function delete()
  {
    DB::beginTransaction();

    $this->image()->delete();

    $this->address()->delete();

    $result = parent::delete();

    DB::commit();

    return $result;
  }
}
