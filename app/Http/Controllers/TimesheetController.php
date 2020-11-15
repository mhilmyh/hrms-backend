<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    private $validateRule = '';

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
        $this->responseHandler(['timesheets' => null]);
    }

    /**
     * Create timesheet
     * 
     * @return object timesheet
     */
    public function create(Request $request)
    {
        $this->responseHandler();
    }

    /**
     * Delete timesheet
     * 
     * @return boolean value
     */
    public function delete(Request $request)
    {
        $this->responseHandler(['value' => true]);
    }
}
