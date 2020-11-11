<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Email extends Model
{

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'emails';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'message',
    'sender_id',
    'receiver_id',
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
   * Sender detail from relation
   * 
   * @return object
   */
  public function sender()
  {
    return $this->hasOne(User::class, 'id', 'sender_id');
  }

  /**
   * Receiver detail from relation
   * 
   * @return object
   */
  public function receiver()
  {
    return $this->hasOne(User::class, 'id', 'receiver_id');
  }
}
