<?php

namespace Database\Seeders;

use App\Models\Employee;
use Faker;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Employee::factory()
            ->times(20)
            ->create();
        $edtEmp = Employee::find(21);
        $edtEmp->user_id = 26;
        $edtEmp->save();
    }
}
