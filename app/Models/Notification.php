<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Notification extends Model
{

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'notifications';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'message',
    'is_read',
    'user_id',
  ];

  /**
   * The model's default values for attributes.
   *
   * @var array
   */
  protected $attributes = [
    // attributes with default value
    'is_read' => false,
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'is_read' => 'boolean',
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
   * User who owns this notification
   * 
   * @return object
   */
  public function user()
  {
    return $this->hasOne(User::class, 'id', 'user_id');
  }
}
