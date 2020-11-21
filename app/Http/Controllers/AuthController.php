<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\User;
use App\Models\Employee;
use App\Models\Address;
use App\Models\Notification;

class AuthController extends Controller
{
  private $validateRule = [
    'login' => [
      'email' => 'required|email',
      'password' => 'required|string|min:6',
    ],
    'register' => [
      'email' => 'required|unique:users|email',
      'password' => 'required|string|min:6',
      'secret' => 'nullable|string',

      'first_name' => 'required|string',
      'mid_name' => 'nullable|string',
      'last_name' => 'required|string',
      'phone' => 'required|string|between:8,16',
      'gender' => 'required|in:Male,Female,Unknown',
      'birthday' => 'required|date',

      'country' => 'required|string',
      'province' => 'required|string',
      'city' => 'required|string',
      'postal_code' => 'required|string',
      'street' => 'required|string',

      // 'salary' => 'required|integer',
      // 'job_position' => 'required|string',
      // 'image_id' => 'nullable|integer',
      // 'address_id' => 'nullable|integer',
      // 'supervisor_id' => 'nullable|integer',
      // 'department_id' => 'nullable|integer',
    ]
  ];

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth', ['except' => [
      'login',
      'register'
    ]]);
  }

  /**
   * Login controller
   *
   * @return string token
   */
  public function login(Request $request)
  {
    $this->validate($request, $this->validateRule['login']);

    // find user with certain email
    $user = User::where('email', $request->input('email'))->first();

    if (!$user)
      return $this->responseHandler(null, 404, 'User not found');

    // check password
    if (!Hash::check($request->input('password'), $user->password))
      return $this->responseHandler(null, 400, 'Password not match');

    $user->is_login = true;
    $user->save();

    // set token
    $token = JWTAuth::fromUser($user);
    return $this->responseHandler(['token' => $token]);
  }

  /**
   * Logout controller
   * 
   * @return null
   */
  public function logout()
  {
    // check authenticated user
    if (!$auth = auth()->user())
      return $this->responseHandler(null, 401, 'Token invalid');

    // set logout
    $user = User::find($auth->id);
    $user->is_login = false;
    $user->save();

    return $this->responseHandler(null, 200, 'Successfully logout');
  }

  /**
   * Get user controller
   *
   * @return object user
   */
  public function user()
  {
    // check authenticated user
    if (!$auth = auth()->user())
      return $this->responseHandler(null, 401, 'Token invalid');

    $user = User::with([
      'employee.address',
      'employee.supervisor',
      'employee.department',
      'employee.image'
    ])->where('id', $auth->id)->first();

    return $this->responseHandler(['user' => $user]);
  }

  /**
   * Register controller
   * 
   * @return null
   */
  public function register(Request $request)
  {
    $this->validate($request, $this->validateRule['register']);

    // create user
    $user = User::create([
      'email' => $request->input('email'),
      'password' => Hash::make($request->input('password')),
      'is_admin' => env('ADMIN_SECRET') == $request->input('secret')
    ]);

    // create address
    $address = Address::create([
      'country' => $request->input('country'),
      'province' => $request->input('province'),
      'city' => $request->input('city'),
      'postal_code' => $request->input('postal_code'),
      'street' => $request->input('street'),
    ]);

    // create employee
    $employee = Employee::create([
      'first_name' => $request->input('first_name'),
      'mid_name' => $request->input('mid_name'),
      'last_name' => $request->input('last_name'),
      'phone' => $request->input('phone'),
      'gender' => $request->input('gender'),
      'birthday' => $request->input('birthday'),
      'user_id' => $user->id,
      'address_id' => $address->id
    ]);

    // save employee id
    $user->employee_id = $employee->id;
    $user->save();

    return $this->responseHandler(null, 201, 'Successfully register user');
  }

  /**
   * Notification controller
   * 
   * @return null
   */
  public function notification($id = null)
  {
    // find notification
    $notification = Notification::find($id);

    if (!$notification) {
      return $this->responseHandler(null, 404, 'Notifcation not found');
    }

    // delete notification
    $notification->delete();

    return $this->responseHandler(null, 200, 'Notification removed');
  }
}
