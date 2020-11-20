<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\throwException;

class UserController extends Controller
{
    private $validateRule = [
        'update' => [
            'email' => 'sometimes|nullable|email|unique:users',
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
        ],
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
        $users = User::all();

        return $this->responseHandler(['users' => $users], 200, $users);
    }

    /**
     * Update user
     *
     * @return object user
     */
    public function update(Request $request)
    {
        $id = intval($request->query('id'));

        // TODO: find one user
        $user = User::find($id);
        $employee = Employee::find($user->employee_id);

        // TODO: update all the data and save
        $user->email = $request->input("email") == null ? $user->email : $request->input("email");
        $user->password = $request->input("password") == null ? $user->password : $request->input("password");
        $employee->first_name = $request->input("first_name") == null ? $employee->first_name : $request->input("first_name");
        $employee->mid_name = $request->input("mid_name") == null ? $employee->mid_name : $request->input("mid_name");
        $employee->last_name = $request->input("last_name") == null ? $employee->last_name : $request->input("last_name");
        $employee->phone = $request->input("phone") == null ? $employee->phone : $request->input("phone");
        $employee->gender = $request->input("gender") == null ? $employee->gender : $request->input("gender");
        $employee->birthday = $request->input("birthday") == null ? $employee->birthday : $request->input("birthday");
        $employee->salary = $request->input("salary") == null ? $employee->salary : $request->input("salary");
        $employee->job_position = $request->input("job_position") == null ? $employee->job_position : $request->input("job_position");
        $employee->rating = $request->input("rating") == null ? $employee->rating : $request->input("rating");
        $this->validate($request, $this->validateRule['update']);

        $employee->save();
        $user->save();

        return $this->responseHandler([$user, $employee], 200, "Successfully update user");
    }

    /**
     * Delete user
     *
     * @return boolean value
     */
    public function delete(Request $request)
    {
        $id = intval($request->query('id'));
        // TODO: find and delete user
        Employee::where('user_id', $id)->delete();
        User::find($id)->delete();

        return $this->responseHandler(['value' => true], 200, "Successfully delete user");
    }
}
