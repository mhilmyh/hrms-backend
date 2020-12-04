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
   * Append new value to response
   * 
   * @var array
   */
  protected $appends = ['full_address'];

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
  public function getFullAddressAttribute()
  {
    return $this->country . ', ' . $this->province . ', ' . $this->city . ', ' . $this->postal_code . ', ' . $this->street;
  }
}
