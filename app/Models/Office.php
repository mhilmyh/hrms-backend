<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Department;
use App\Models\Address;
use App\Models\Image;

class Office extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'offices';
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'opening_time',
    'closing_time',
    'building',
    'is_branch',
    'address_id',
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
    'is_branch' => 'boolean'
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
   * Address of office
   *
   * @return object
   */
  public function address()
  {
    return $this->hasOne(Address::class, 'id', 'address_id');
  }

  /**
   * Departments of office
   *
   * @return object
   */
  public function departments()
  {
    return $this->hasMany(Department::class, 'id', 'address_id');
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

    $this->departments()->each(function ($activity) {
      $activity->delete();
    });

    $result = parent::delete();

    DB::commit();

    return $result;
  }
}
