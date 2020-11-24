<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;

use App\Models\Activity;
use App\Models\Timesheet;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    private $validateRule = [
        'create' => [
            'activities' => 'required|array',
            'activities.*.desc' => 'required|string',
            'activities.*.start_time' => 'required|date_format:H:i',
            'activities.*.stop_time' => 'required|date_format:H:i',
            // 'user_id' => 'required|integer',
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
    public function index()
    {
        $today_timesheets = Timesheet::with(['user.employee', 'activities'])->where('created_at', '>=', Carbon::today())->get();
        $timesheets = Timesheet::with(['user.employee'])->get();
        return $this->responseHandler(['timesheets' => $timesheets, 'today_timesheets' => $today_timesheets]);
    }

    /**
     * Create timesheet
     * 
     * @return object timesheet
     */
    public function create(Request $request)
    {
        $this->validate($request, $this->validateRule['create']);

        $timesheet = Timesheet::create([
            'user_id' => auth()->user()->id
        ]);

        $activities = $request->input('activities');
        $construct = function ($activity) use ($timesheet) {
            return array_merge($activity, ['timesheet_id' => $timesheet->id]);
        };
        $data = array_map($construct, $activities);

        Activity::insert($data);

        return $this->responseHandler(null, 200, 'Successfully create timesheet');
    }

    /**
     * Delete timesheet
     * 
     * @return boolean value
     */
    public function delete($id = null)
    {
        if (!auth()->user()->is_admin)
            return $this->responseHandler(null, 400, 'You are not admin');

        $success = Timesheet::destroy($id);

        if (!$success)
            return $this->responseHandler(null, 400, 'Failed to delete timesheet');

        return $this->responseHandler(null, 200, 'Timesheet deleted successfully');
    }
}
