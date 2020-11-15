<?php

namespace App\Http\Controllers;

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
    public function index(Request $request)
    {
        // TODO: Get all office and department

        $this->responseHandler();
    }

    /**
     * Create office or department
     * 
     * @return object office or department
     */
    public function create(Request $request)
    {
        // TODO: check if client want to create office or department
        // TODO: apply validate request (office or department)
        // TODO: store office or department

        $this->responseHandler();
    }

    /**
     * Update office or department
     * 
     * @return object updated office or department
     */
    public function update(Request $request)
    {
        // TODO: check if client want to update office or department
        // TODO: apply validate request (office or department)
        // TODO: update office or department

        $this->responseHandler();
    }

    /**
     * Delete office or department
     * 
     * @return boolean value
     */
    public function delete(Request $request)
    {
        // TODO: check if client want to delete office or department

        $this->responseHandler(['value' => true]);
    }
}
