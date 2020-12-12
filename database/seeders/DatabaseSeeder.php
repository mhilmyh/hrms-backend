<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Image;
use App\Models\Notification;
use App\Models\Office;
use App\Models\Timesheet;
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
            UserSeeder::class,
            ActivitySeeder::class,
            AddressSeeder::class,
            OfficeSeeder::class,
            DepartmentSeeder::class,
            ImageSeeder::class,
            NotificationSeeder::class,
            TimesheetSeeder::class,
            EmployeeSeeder::class,
        ]);
    }
}
