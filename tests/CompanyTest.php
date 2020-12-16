<?php

use Tymon\JWTAuth\Facades\JWTAuth;
use Faker\Factory;
use App\Models\User;
use App\Models\Office;
use App\Models\Department;
class CompanyTest extends TestCase
{
    
    public function testShouldReturnAllCompanies()
    {
        $this->get('/api/company')
            ->seeStatusCode(200)
            ->seeJsonStructure(['message', 'offices', 'departments']);
    }

    public function testShouldCreateOfficeSuccess() {
        $user = User::latest()->first();
        $user->is_admin = true;
        $user->save();
        $user = User::latest()->first();
        $this->assertTrue(
            $user->is_admin === true, 
            'User role must be admin'
        );
        $token = JWTAuth::fromUser($user);
        $faker = Factory::create();
        $data = [
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
        $this->post('/api/company/office?token=' . $token, $data)
            ->seeStatusCode(201)
            ->seeJsonEquals([
                'message' => 'Successfully create office'
            ]);
    }

    public function testShouldCreateDeptSuccess() {
        $user = User::latest()->first();
        $user->is_admin = true;
        $user->save();
        $token = JWTAuth::fromUser($user);
        $faker = Factory::create();
        $data = [
            'name' => $faker->company,
            'code' => 'AAA',
            'chairman_id' => User::all()->pluck('id')->random(),
            'office_id' => Office::all()->pluck('id')->random()
        ];

        $this->post('/api/company/department?token=' . $token, $data)
            ->seeStatusCode(201)
            ->seeJsonEquals([
                'message' => 'Successfully create department'
            ]);
    }

    public function testShouldUpdateOfficeSuccess() {
        $user = User::latest()->first();
        $office_id = Office::all()->pluck('id')->random();
        $token = JWTAuth::fromUser($user);
        $faker = Factory::create();
        $data = [
            'id' => $office_id,
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
        $this->put('/api/company/office?token=' . $token, $data)
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'message' => 'Update Successfully'
            ]);
    }

    public function testShouldUpdateDeptSuccess() {
        $user = User::latest()->first();
        $user->is_admin = true;
        $user->save();
        $dept_id = Department::all()->pluck('id')->random();
        $token = JWTAuth::fromUser($user);
        $faker = Factory::create();
        $data = [
            'id' => $dept_id,
            'name' => $faker->company,
            'code' => 'AAA',
            'chairman_id' => User::all()->pluck('id')->random(),
            'office_id' => Office::all()->pluck('id')->random()
        ];
        $this->put('/api/company/department?token='.$token, $data)
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'message' => 'Update Successfully'
            ]);
    }

    public function testShouldDeleteOfficeSuccess() {
        $user = User::latest()->first();
        $user->is_admin = true;
        $user->save();
        $office_id = Office::all()->pluck('id')->random();
        $token = JWTAuth::fromUser($user);
        $this->delete('/api/company/office/'.$office_id.'?token='.$token)
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'message' => 'Successfully delete office'
            ]);
    }

    public function testShouldDeleteDeptSuccess() {
        $user = User::latest()->first();
        $user->is_admin = true;
        $user->save();
        $dept_id = Department::all()->pluck('id')->random();
        $token = JWTAuth::fromUser($user);
        $this->delete('/api/company/department/'.$dept_id.'?token='.$token)
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'message' => 'Successfully delete department'
            ]);
    }

}
