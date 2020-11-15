<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    private $validateRule = [
        'created' => [
            'activities' => 'required|array',
            'activities.*.desc' => 'required|string',
            'activities.*.start_time' => 'required|date_format:H:i',
            'activities.*.stop_time' => 'required|date_format:H:i',
            'user_id' => 'required|integer',
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
     * Get timesheets
     * 
     * @return array timesheet
     */
    public function index(Request $request)
    {
        // TODO: get all time sheet

        $this->responseHandler(['timesheets' => null]);
    }

    /**
     * Create timesheet
     * 
     * @return object timesheet
     */
    public function create(Request $request)
    {
        $this->validate($request, $this->validateRule['create']);

        // TODO: store activity and then store timesheet

        $this->responseHandler();
    }

    /**
     * Delete timesheet
     * 
     * @return boolean value
     */
    public function delete(Request $request)
    {
        // TODO: find and delete timesheet

        $this->responseHandler(['value' => true]);
    }
}
