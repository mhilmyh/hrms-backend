<?php

namespace App\Http\Controllers;

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
                'building' => 'required|string',
                'is_branch' => 'nullable|boolean',
                'head_office_id' => 'nullable|integer',
                'image_id' => 'nullable|integer',
                'country' => 'required|string',
                'province' => 'required|string',
                'city' => 'required|string',
                'subdistrict' => 'required|string',
                'postal_code' => 'required|string',
                'street' => 'required|string'
            ],
            'update' => [
                'name' => 'nullable|string',
                'opening_time' => 'nullable|date_format:H:i',
                'closing_time' => 'nullable|date_format:H:i',
                'building' => 'nullable|string',
                'is_branch' => 'nullable|boolean',
                'head_office_id' => 'nullable|integer',
                'image_id' => 'nullable|integer',
                'country' => 'nullable|string',
                'province' => 'nullable|string',
                'city' => 'nullable|string',
                'subdistrict' => 'nullable|string',
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
        $offices = Office::with(['head_office', 'image', 'address', 'departments'])->get();
        $departments = Department::with(['chairman.employee', 'office'])->get();

        return $this->responseHandler(['offices' => $offices, 'departments' => $departments]);
    }

    /**
     * Create office or department
     * 
     * @return object office or department
     */
    public function create(Request $request, $identifier = "")
    {
        if (!auth()->user()->is_admin)
            return $this->responseHandler(null, 400, 'You are not admin');

        $this->validate($request, $this->validateRule[$identifier]['create']);

        if ($identifier === "office")
            Office::create($request->all());
        else if ($identifier === "department")
            Department::create($request->all());
        else
            return $this->responseHandler(null, 404, "Wrong identifier");

        return $this->responseHandler(null, 200, 'Successfully create ' . $identifier);
    }

    /**
     * Update office or department
     * 
     * @return object updated office or department
     */
    public function update(Request $request, $identifier = "")
    {
        if (!auth()->user()->is_admin)
            return $this->responseHandler(null, 400, 'You are not admin');

        $this->validate($request, $this->validateRule[$identifier]['update']);

        if ($identifier === "office")
            Office::create($request->all());
        else if ($identifier === "department")
            Department::create($request->all());
        else
            return $this->responseHandler(null, 404, "Wrong identifier");

        return $this->responseHandler(null, 200, 'Update Successfully');
    }

    /**
     * Delete office or department
     * 
     * @return boolean value
     */
    public function delete($identifier = "", $id = null)
    {
        if (!auth()->user()->is_admin)
            return $this->responseHandler(null, 400, 'You are not admin');

        $success = false;
        if ($identifier === "office")
            $success = Office::destroy($id);
        else if ($identifier === "department")
            $success = Department::destroy($id);
        else
            return $this->responseHandler(null, 404, "Wrong identifier");

        if (!$success) return $this->responseHandler(null, 400, "Failed to delete " . $identifier);

        return $this->responseHandler(null, 200, 'Successfully delete ' . $identifier);
    }
}
