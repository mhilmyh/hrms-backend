<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


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
    $user = User::where('email', $request->email);

    // Get the token
    $credentials = request(['email', 'password']);
    if (! $token = auth()->attempt($credentials)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    $user->is_login = true;
    $user.save();

    // TODO: return JWT token
    return $this->respondWithToken($token);

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
    $user = auth()->user();

    // TODO: set is_login attribute to false
    $user->is_login = false;
    $user.save();

    auth()->logout();

    return response()->json(['message' => 'Successfully logged out']);

    //return $this->responseHandler();
  }

  /**
   * Get user controller
   *
   * @return object user
   */
  public function user(Request $request)
  {
    // TODO: find user from authenticated id
    //$user = JWTAuth::toUser($token);
    $user = auth()->user();

    // TODO: return user as a response
    return response()->json(compact('user'));

    //return $this->responseHandler();
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
    $email = $request->input("email");
    $password = $request->input("password");

    $data = [
      "email" => $email,
      "password" => $password
    ];
    User::create($data);

    // TODO: create employee
    $user = User::where('email', $request->email);
    echo $user;
    Employee::create([
      'first_name' => $request->first_name,
      'mid_name' => $request->mid_name,
      'last_name' => $request->last_name,
      'phone' => $request->phone,
      'gender' => $request->gender,
      'birthday' =>$request->birthday,
      'salary' => $request->salary,
      'job_position' => $request->job_position,
      'rating' => $request->rating,
      'user_id' => $user->id,
    ]);

    // TODO: create address
    Address::create([
      'country' => $request->country,
      'province' => $request->province,
      'city' => $request->city,
      'subdistrict' => $request->subdistrict,
      'postal_code' => $request->postal_code,
      'street' => $request->street,
    ]);

    // TODO: save

    return $this->responseHandler([
      'value' => true
    ], 201, 'Successfully register user');
  }
}
