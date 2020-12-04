<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Office;
use App\Models\Timesheet;

class GeneralController extends Controller
{
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
   * Dashboard controller
   *
   * @return object summary
   */
  public function dashboard()
  {
    $count_employee = Employee::count();
    $count_office = Office::count();
    $count_department = Department::count();
    $count_timesheet = Timesheet::count();

    $latest_timesheet = Timesheet::with('user.employee')->where('created_at', '>=', Carbon::today())->orderBy('created_at', 'desc')->get();

    $best_employee = Employee::orderBy('rating', 'desc')->limit(3)->get();

    return $this->responseHandler([
      'count_employee' => $count_employee,
      'count_office' => $count_office,
      'count_department' => $count_department,
      'count_timesheet' => $count_timesheet,
      'latest_timesheet' => $latest_timesheet,
      'best_employee' => $best_employee
    ]);
  }

  /**
   * Reset rating controller
   * 
   * @return null
   */
  public function reset()
  {
    if (!auth()->user()->is_admin)
      return $this->responseHandler(null, 400, 'You are not admin');

    Employee::query()->update(['rating' => 0]);

    return $this->responseHandler(null, 200, 'Rating reset successfully');
  }
}
