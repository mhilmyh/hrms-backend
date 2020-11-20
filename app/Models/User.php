<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;

use App\Models\Employee;
use App\Models\Notification;
use App\Models\Email;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'is_admin',
        'is_login',
        'employee_id',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        // attributes with default value
        'is_admin' => false,
        'is_login' => false,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_admin' => 'boolean',
        'is_login' => 'boolean',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the identifier that will be stored in the subject
     * claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims
     * to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The "booted" method of the model
     *
     * @return void
     */
    public static function booted()
    {
        static::saving(function ($model) {
            $model->password = Hash::make($model->password);
        });
    }

    /**
     * Employee detail from relation
     *
     * @return object
     */
    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    /**
     * Notification user
     *
     * @return array
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id');
    }

    /**
     * Email send to this user
     *
     * @return array
     */
    public function emails()
    {
        return $this->hasMany(Email::class, 'user_id', 'id');
    }

    public function testDatabase()
    {
        $user = User::factory()->make();

        // Use model in tests...
    }
}
