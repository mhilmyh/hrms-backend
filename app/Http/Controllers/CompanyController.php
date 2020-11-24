<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Department;
use App\Models\Office;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    private $validateRule = [
        'office' => [
            'create' => [
                'name' => 'required|string',
                'opening_time' => 'required|date_format:H:i',
                'closing_time' => 'required|date_format:H:i',
                'building' => 'nullable|string',
                'is_branch' => 'nullable|boolean',
                'country' => 'required|string',
                'province' => 'required|string',
                'city' => 'required|string',
                'postal_code' => 'required|string',
                'street' => 'required|string'
            ],
            'update' => [
                'name' => 'nullable|string',
                'opening_time' => 'nullable|date_format:H:i',
                'closing_time' => 'nullable|date_format:H:i',
                'building' => 'nullable|string',
                'is_branch' => 'nullable|boolean',
                'country' => 'nullable|string',
                'province' => 'nullable|string',
                'city' => 'nullable|string',
                'postal_code' => 'nullable|string',
                'street' => 'nullable|string'
            ],
        ],
        'department' => [
            'create' => [
                'name' => 'required|string',
                'code' => 'required|string',
                'chairman_id' => 'nullable|integer',
                'office_id' => 'nullable|integer',
            ],
            'update' => [
                'name' => 'nullable|string',
                'code' => 'nullable|string',
                'chairman_id' => 'nullable|integer',
                'office_id' => 'nullable|integer',
            ],
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
     * Get offices and departments
     * 
     * @return array offices and departments
     */
    public function index()
    {
        $offices = Office::with(['address', 'departments'])->get();
        $departments = Department::with(['chairman.employee', 'office'])->get();

        return $this->responseHandler(['offices' => $offices, 'departments' => $departments]);
    }

    /**
     * Get offices
     * 
     * @return array offices
     */
    public function offices()
    {
        $offices = Office::all();

        return $this->responseHandler(['offices' => $offices]);
    }

    /**
     * Get departments
     * 
     * @return array departments
     */
    public function departments()
    {
        $departments = Department::all();

        return $this->responseHandler(['departments' => $departments]);
    }

    /**
     * Create office or department
     * 
     * @return object office or department
     */
    public function create(Request $request, $identifier = '')
    {
        if (!auth()->user()->is_admin)
            return $this->responseHandler(null, 400, 'You are not admin');

        $this->validate($request, $this->validateRule[$identifier]['create']);

        if ($identifier === 'office') {
            $address = Address::create([
                'country' => $request->input('country'),
                'province' => $request->input('province'),
                'city' => $request->input('city'),
                'postal_code' => $request->input('postal_code'),
                'street' => $request->input('street'),
            ]);

            Office::create([
                'name' => $request->input('name'),
                'opening_time' => $request->input('opening_time'),
                'closing_time' => $request->input('closing_time'),
                'building' => $request->input('building'),
                'is_branch' => $request->input('is_branch'),
                'address_id' => $address->id,
            ]);
        } else if ($identifier === 'department') {
            Department::create([
                'name' => $request->input('name'),
                'code' => $request->input('code'),
                'chairman_id' => $request->input('chairman_id'),
                'office_id' => $request->input('office_id'),
            ]);
        } else
            return $this->responseHandler(null, 404, 'Wrong identifier');

        return $this->responseHandler(null, 200, 'Successfully create ' . $identifier);
    }

    /**
     * Update office or department
     * 
     * @return object updated office or department
     */
    public function update(Request $request, $identifier = '')
    {
        if (!auth()->user()->is_admin)
            return $this->responseHandler(null, 400, 'You are not admin');

        $this->validate($request, $this->validateRule[$identifier]['update']);

        if ($identifier === 'office') {
            $office = Office::find($request->input('id'));
            $address = Address::find($office->address_id);

            $office->fill([
                'name' => $request->input('name') === null ? $office->name : $request->input('name'),
                'opening_time' => $request->input('opening_time') === null ? $office->opening_time : $request->input('opening_time'),
                'closing_time' => $request->input('closing_time') === null ? $office->closing_time : $request->input('closing_time'),
                'building' => $request->input('building') === null ? $office->building : $request->input('building'),
                'is_branch' => $request->input('is_branch') === null ? $office->is_branch : $request->input('is_branch'),
            ])->save();

            $address->fill([
                'country' => $request->input('country') === null ? $address->country : $request->input('country'),
                'province' => $request->input('province') === null ? $address->province : $request->input('province'),
                'city' => $request->input('city') === null ? $address->city : $request->input('city'),
                'postal_code' => $request->input('postal_code') === null ? $address->postal_code : $request->input('postal_code'),
                'street' => $request->input('street') === null ? $address->street : $request->input('street'),
            ])->save();
        } else if ($identifier === 'department') {
            $department = Department::find($request->input('id'));
            $department->fill($request->all())->save();
        } else
            return $this->responseHandler(null, 404, 'Wrong identifier');

        return $this->responseHandler(null, 200, 'Update Successfully');
    }

    /**
     * Delete office or department
     * 
     * @return boolean value
     */
    public function delete($identifier = '', $id = null)
    {
        if (!auth()->user()->is_admin)
            return $this->responseHandler(null, 400, 'You are not admin');

        $success = false;
        if ($identifier === 'office')
            $success = Office::destroy($id);
        else if ($identifier === 'department')
            $success = Department::destroy($id);
        else
            return $this->responseHandler(null, 404, 'Wrong identifier');

        if (!$success) return $this->responseHandler(null, 400, 'Failed to delete ' . $identifier);

        return $this->responseHandler(null, 200, 'Successfully delete ' . $identifier);
    }
}
