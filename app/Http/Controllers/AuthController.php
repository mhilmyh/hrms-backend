<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
  private $validateRule = [
    'login' => [
      'email' => 'required|email',
      'password' => 'required|string|min:6',
      'secret' => 'nullable|string'
    ],
    'register' => [
      'email' => 'required|unique:users|email',
      'password' => 'required|string|min:6',
      'secret' => 'nullable|string',
      'first_name' => 'required|string',
      'mid_name' => 'nullable|string',
      'last_name' => 'required|string',
      'phone' => 'required|string|between:8,16',
      'gender' => 'required|in:M,F,U',
      'birthday' => 'required|date',
      'salary' => 'required|integer',
      'job_position' => 'required|string',
      'image_id' => 'nullable|integer',
      'address_id' => 'nullable|integer',
      'supervisor_id' => 'nullable|integer',
      'department_id' => 'nullable|integer',
      'country' => 'required|string',
      'province' => 'required|string',
      'city' => 'required|string',
      'subdistrict' => 'required|string',
      'postal_code' => 'required|string',
      'street' => 'required|string',
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

    // TODO: find user with certain email
    // TODO: return JWT token

    return $this->responseHandler(['token' => null]);
  }

  /**
   * Logout controller
   * 
   * @return void null
   */
  public function logout(Request $request)
  {
    // TODO: find user with id is equal to authenticated id
    // TODO: set is_login attribute to false

    return $this->responseHandler();
  }

  /**
   * Get user controller
   * 
   * @return object user
   */
  public function user(Request $request)
  {
    // TODO: find user from authenticated id
    // TODO: return user as a response

    return $this->responseHandler();
  }

  /**
   * Register controller
   * 
   * @return true value
   */
  public function register(Request $request)
  {
    $this->validate($request, $this->validateRule['register']);

    // TODO: create user
    // TODO: create employee
    // TODO: create address
    // TODO: save

    return $this->responseHandler([
      'value' => true
    ], 201, 'Successfully register user');
  }
}
