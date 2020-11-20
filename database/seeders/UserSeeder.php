<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->times(20)->create();
        $edtEmp = User::find(26);
        $edtEmp->employee_id = 21;
        $edtEmp->save();
    }
}
