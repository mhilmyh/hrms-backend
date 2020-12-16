<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Image;
use App\Models\Notification;
use App\Models\Office;
use App\Models\Timesheet;
use App\Models\User;
use Departments;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // UserSeeder::class,
            // AddressSeeder::class,
            // OfficeSeeder::class,
            // DepartmentSeeder::class,
            // ImageSeeder::class,
            // NotificationSeeder::class,
            // TimesheetSeeder::class,
            // ActivitySeeder::class,
            // EmployeeSeeder::class,
        ]);

        // $users = User::all();
        // $employees = Employee::all();
        // for ($i = 1; $i <= min(sizeof($users), sizeof($employees)); $i++) {
        //     $user = User::find($i);
        //     $user->employee_id = $i;
        //     $employee = Employee::find($i);
        //     $employee->user_id = $i;
        //     $employee->supervisor->id = random_int(1, sizeof($users));
        //     $user->save();
        //     $employee->save();
        // }
    }
}
