<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

  public function image()
  {
    return $this->hasOne(Image::class, 'id', 'image_id');
  }

    public function address()
    {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }

  public function user(){
      return $this->belongsTo(User::class, 'user_id', 'id');
  }
}
