<?php

namespace Database\Seeders;

use App\Models\Timesheet;
use Illuminate\Database\Seeder;

class TimesheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Timesheet::factory()->times(40)->create();
    }
}
