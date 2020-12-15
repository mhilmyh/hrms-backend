<?php

use App\Models\Department;
use App\Models\Office;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth;

class CompanyTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShouldReturnAllCompanies()
    {
        $this->get('/api/company')->seeStatusCode(200);
    }

    public function testShouldReturnAllOfficesSuccess() {
        $user = User::find(1);
        $token = JWTAuth::fromUser($user);
        $user->is_login = true;
        $this->get('/api/company/offices?token='.$token)->seeStatusCode(200);
    }

    public function testShouldReturnAllDeptsSuccess() {
        $user = User::find(1);
        $token = JWTAuth::fromUser($user);
        $user->is_login = true;
        $this->get('/api/company/departments?token='.$token)->seeStatusCode(200);
    }

    public function testShouldCreateOfficeSuccess() {
        $user = User::find(1);
        $user->is_admin = true;
        $user->is_login = true;
        $user->save();
        $token = JWTAuth::fromUser($user);
        $faker = Faker\Factory::create();
        $params = [
            'name' => $faker->company,
            'opening_time' => '07:00',
            'closing_time' => '16:00',
            'building' => 'Building '.strval(random_int(1, 10)),
            'is_branch' => $faker->boolean(50),

            'country' => $faker->country,
            'province' => $faker->state,
            'city' => $faker->city,
            'postal_code' => '12193',
            'street' => $faker->streetAddress

        ];

        $this->post('/api/company/office?token='.$token, $params, [])->seeStatusCode(200);
        $user->is_admin = false;
        $user->is_login = false;
        $user->save();
    }

    public function testShouldCreateDeptSuccess() {
        $user = User::find(1);
        $user->is_admin = true;
        $user->is_login = true;
        $user->save();
        $token = JWTAuth::fromUser($user);
        $faker = Faker\Factory::create();
        $params = [
            'name' => $faker->company,
            'code' => 'AAA',
            'chairman_id' => User::all()->pluck('id')->random(),
            'office_id' => Office::all()->pluck('id')->random()
        ];

        $this->post('/api/company/department?token='.$token, $params, [])->seeStatusCode(200);
        $user->is_admin = false;
        $user->is_login = false;
        $user->save();
    }

    public function testShouldUpdateOfficeSuccess() {
        $this->testShouldCreateOfficeSuccess();
        $user = User::find(1);
        $user->is_admin = true;
        $user->is_login = true;
        $user->save();
        $officeId = Office::all()->pluck('id')->random();
        $token = JWTAuth::fromUser($user);
        $faker = Faker\Factory::create();
        $params = [
            'id' => $officeId,
            'name' => $faker->company,
            'opening_time' => '09:00',
            'closing_time' => '18:00',
            'building' => 'Building '.strval(random_int(1, 10)),
            'is_branch' => $faker->boolean(50),

            'country' => $faker->country,
            'province' => $faker->state,
            'city' => $faker->city,
            'postal_code' => '99999',
            'street' => $faker->streetAddress

        ];

        $this->post('/api/company/office?token='.$token, $params, [])->seeStatusCode(200);
        $user->is_admin = false;
        $user->is_login = false;
        $user->save();
    }

    public function testShouldUpdateDeptSuccess() {
        $this->testShouldCreateDeptSuccess();
        $user = User::find(1);
        $user->is_admin = true;
        $user->is_login = true;
        $user->save();
        $deptId = Department::all()->pluck('id')->random();
        $token = JWTAuth::fromUser($user);
        $faker = Faker\Factory::create();
        $params = [
            'id' => $deptId,
            'name' => $faker->company,
            'code' => 'AAA',
            'chairman_id' => User::all()->pluck('id')->random(),
            'office_id' => Office::all()->pluck('id')->random()
        ];

        $this->post('/api/company/department?token='.$token, $params, [])->seeStatusCode(200);
        $user->is_admin = false;
        $user->is_login = false;
        $user->save();
    }

    public function testShouldDeleteOfficeSuccess() {
        $this->testShouldCreateOfficeSuccess();
        $user = User::find(1);
        $user->is_admin = true;
        $user->is_login = true;
        $user->save();
        $officeId = Office::all()->pluck('id')->random();
        $token = JWTAuth::fromUser($user);
        $this->delete('/api/company/office/'.$officeId.'?token='.$token)->seeStatusCode(200);
        $user->is_admin = false;
        $user->is_login = false;
        $user->save();
    }

    public function testShouldDeleteDeptSuccess() {
        $this->testShouldCreateDeptSuccess();
        $user = User::find(1);
        $user->is_admin = true;
        $user->is_login = true;
        $user->save();
        $deptId = Department::all()->pluck('id')->random();
        $token = JWTAuth::fromUser($user);
        $this->delete('/api/company/department/'.$deptId.'?token='.$token)->seeStatusCode(200);
        $user->is_admin = false;
        $user->is_login = false;
        $user->save();
    }

}
