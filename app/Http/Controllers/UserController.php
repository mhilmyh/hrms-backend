<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Address;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\User;

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
            'gender' => 'nullable|in:Male,Female,Unknown',
            'birthday' => 'nullable|date',
            'salary' => 'nullable|integer',
            'job_position' => 'nullable|string',
            'rating' => 'nullable|numeric',

            'country' => 'nullable|string',
            'province' => 'nullable|string',
            'city' => 'nullable|string',
            'subdistrict' => 'nullable|string',
            'postal_code' => 'nullable|string',

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
        $this->middleware('auth');
    }

    /**
     * Get all user
     *
     * @return array users
     */
    public function index()
    {
        // get all user
        $users = User::with([
            'employee.address',
            'employee.supervisor',
            'employee.department',
            'employee.image'
        ])->get();

        return $this->responseHandler(['users' => $users]);
    }

    /**
     * Update user
     *
     * @return object user
     */
    public function update(Request $request, $id = null)
    {
        $this->validate($request, $this->validateRule['update']);

        // find user, employee and address
        $user = User::find($id);
        $employee = Employee::find($user->employee_id);
        $address = Address::find($employee->address_id);

        // update user
        $user->email = $request->input("email") === null ? $user->email : $request->input("email");
        $user->password = $request->input("password") === null ? $user->password : Hash::make($request->input('password'));

        // update employee
        $employee->first_name = $request->input("first_name") === null ? $employee->first_name : $request->input("first_name");
        $employee->mid_name = $request->input("mid_name") === null ? $employee->mid_name : $request->input("mid_name");
        $employee->last_name = $request->input("last_name") === null ? $employee->last_name : $request->input("last_name");
        $employee->phone = $request->input("phone") === null ? $employee->phone : $request->input("phone");
        $employee->gender = $request->input("gender") === null ? $employee->gender : $request->input("gender");
        $employee->birthday = $request->input("birthday") === null ? $employee->birthday : $request->input("birthday");
        $employee->salary = $request->input("salary") === null ? $employee->salary : $request->input("salary");
        $employee->job_position = $request->input("job_position") === null ? $employee->job_position : $request->input("job_position");

        $diff = 0;
        if (
            intval($request->input("rating")) >= 0 &&
            intval($employee->rating) >= 0
        ) {

            $diff = intval($request->input("rating")) - intval($employee->rating);
            $message_status = '';

            if ($diff !== 0) {
                if ($diff > 0) $message_status = 'increase';
                else $message_status = 'decrease';

                Notification::create([
                    'user_id' => $user->id,
                    'message' => 'your rating is ' . $message_status,
                ]);
            }
            $employee->rating = $request->input("rating");
        }

        if ($request->input('supervisor_id')) {
            $employee->supervisor_id = $request->input('supervisor_id');
            Notification::create([
                'user_id' => $request->input('supervisor_id'),
                'message' => 'now you are the supervisor of the user ' . $user->email,
            ]);
        }

        if ($request->input('department_id')) {
            $employee->department_id = $request->input('department_id');
            Notification::create([
                'user_id' => $user->id,
                'message' => 'you have moved to another department',
            ]);
        }

        // update address
        $address->country = $request->input("country") === null ? $address->country : $request->input("country");
        $address->province = $request->input("province") === null ? $address->province : $request->input("province");
        $address->city = $request->input("city") === null ? $address->city : $request->input("city");
        $address->postal_code = $request->input("postal_code") === null ? $address->postal_code : $request->input("postal_code");
        $address->street = $request->input("street") === null ? $address->street : $request->input("street");

        // save 
        $address->save();
        $employee->save();
        $user->save();

        return $this->responseHandler(null, 200, "Successfully update user");
    }

    /**
     * Delete user
     *
     * @return null
     */
    public function delete($id = null)
    {
        $user = User::find($id);

        if (!$user)
            return $this->responseHandler(null, 404, 'User not found');

        $user->delete();

        return $this->responseHandler(null, 200, "Successfully delete user");
    }
}
