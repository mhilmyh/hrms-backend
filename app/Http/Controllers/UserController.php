<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    private $validateRule = [
        'update' => [
            'email' => 'nullable|unique:users|email',
            'password' => 'nullable|string|min:6',
            'secret' => 'nullable|string',
            'first_name' => 'nullable|string',
            'mid_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'phone' => 'nullable|string|between:8,16',
            'gender' => 'nullable|in:M,F,U',
            'birthday' => 'nullable|date',
            'salary' => 'nullable|integer',
            'job_position' => 'nullable|string',
            'rating' => 'nullable|numeric',
            'image_id' => 'nullable|integer',
            'address_id' => 'nullable|integer',
            'supervisor_id' => 'nullable|integer',
            'department_id' => 'nullable|integer',
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
            'index'
        ]]);
    }

    /**
     * Get all user
     * 
     * @return array users
     */
    public function index(Request $request)
    {
        // TODO: Get all user and return it from response

        $this->responseHandler(['users' => null]);
    }

    /**
     * Update user
     * 
     * @return object user
     */
    public function update(Request $request)
    {
        $this->validate($request, $this->validateRule['update']);

        // TODO: find one user
        // TODO: update all the data and save

        $this->responseHandler(null, 200, "Successfully update user");
    }

    /**
     * Delete user
     * 
     * @return boolean value
     */
    public function delete(Request $request)
    {
        // TODO: find and delete user

        $this->responseHandler(['value' => true], 200, "Successfully update user");
    }
}
