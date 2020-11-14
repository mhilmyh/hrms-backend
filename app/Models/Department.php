<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Office;

class Department extends Model
{
    use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'departments';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'code',
    'chairman_id',
    'office_id',
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
   * The chairman of department
   *
   * @return object
   */
  public function chairman()
  {
    return $this->hasOne(User::class, 'id', 'chairman_id');
  }

  /**
   * Office of department
   *
   * @return array
   */
  public function offices()
  {
    return $this->hasMany(Office::class, 'id', 'office_id');
  }
}
