<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    'build',
    'is_branch',
    'head_office_id',
    'image_id',
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
   * The chairman of department
   *
   * @return object
   */
  public function head_office()
  {
    return $this->hasOne(Office::class, 'id', 'head_office_id');
  }

  /**
   * Image for office picture
   *
   * @return object
   */
  public function image()
  {
    return $this->hasOne(Image::class, 'id', 'image_id');
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
   * @return array
   */
  public function departments()
  {
    return $this->hasMany(Department::class, 'id', 'address_id');
  }
}
