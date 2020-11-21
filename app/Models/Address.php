<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    use HasFactory;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'addresses';
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'country',
    'province',
    'city',
    'subdistrict',
    'postal_code',
    'street',
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

  public function employee() {
      return $this->belongsTo(Employee::class, 'address_id', 'id');
  }

  public function userAddress()
    {
        return $this->hasOneThrough(
            'App\Models\User',
            'App\Models\Employee',
            'address_id',
            'employee_id',
            'id',
            'id'
        );
    }
}
