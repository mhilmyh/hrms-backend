<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Employee;
use App\Models\User;
use App\Models\Address;
use App\Models\Notification;

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

        $user = User::find($id);
        $employee = Employee::find($user->employee_id);
        $address = Address::find($employee->address_id);

        $user->email = $request->input('email') === null ? $user->email : $request->input('email');
        $user->password = $request->input('password') === null ? $user->password : Hash::make($request->input('password'));

        $employee = $this->updateEmployee($request, $employee);

        $diff = 0;
        if (
            intval($request->input('rating')) >= 0 &&
            intval($employee->rating) >= 0
        ) {

            $diff = intval($request->input('rating')) - intval($employee->rating);
            $message_status = '';

            if ($diff !== 0) {
                if ($diff > 0) {
                    $message_status = 'increase';
                }
                else {
                    $message_status = 'decrease';
                }

                Notification::create([
                    'user_id' => $user->id,
                    'message' => 'your rating is ' . $message_status,
                ]);
            }
            $employee->rating = $request->input('rating');
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

        $address = $this->updateAddress($request, $address);

        $address->save();
        $employee->save();
        $user->save();

        return $this->responseHandler(null, 200, 'User updated successfully');
    }

    /**
     * Delete user
     *
     * @return null
     */
    public function delete($id = null)
    {
        if (!auth()->user()->is_admin)
            return $this->responseHandler(null, 400, 'You are not admin');

        $user = User::find($id);

        if (!$user)
            return $this->responseHandler(null, 404, 'User not found');

        $user->delete();

        return $this->responseHandler(null, 200, "User deleted successfully");
    }
}
