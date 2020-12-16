<?php

use App\Models\Address;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserTest extends TestCase
{
    public function testShouldReturnAllUsers()
    {
        $user = User::latest()->first();
        $token = JWTAuth::fromUser($user);
        $this->get('/api/user?token=' . $token)
            ->seeStatusCode(200)
            ->seeJsonStructure([
                "users" => ['*' => [
                    "id",
                    "email",
                    "is_admin",
                    "is_login",
                    "employee_id",
                    "created_at",
                    "updated_at",
                    "employee" => [
                        "id",
                        "first_name",
                        "mid_name",
                        "last_name",
                        "phone",
                        "gender",
                        "birthday",
                        "salary",
                        "job_position",
                        "rating",
                        "user_id",
                        "image_id",
                        "address_id",
                        "supervisor_id",
                        "department_id",
                        "created_at",
                        "updated_at",
                        "full_name",
                        "address" => [
                            "id",
                            "country",
                            "province",
                            "city",
                            "postal_code",
                            "street",
                            "full_address"
                        ],
                        "supervisor",
                        "department",
                        "image"
                    ]
                ]],
                "message"
            ]);
    }

    public function testShouldReturnUnauthorized() {
        $this->get('/api/user')
            ->seeStatusCode(401)
            ->seeJsonEquals([
                'message' => 'Unauthorized'
            ]);
    }

    public function testShouldUpdate(){
        $user = User::latest()->first();
        $token = JWTAuth::fromUser($user);
        $data = [
            'email' => 'user@updated.com',
            'password' => Hash::make('1234567890'),
            'first_name' => 'Petelgeuse',
            'mid_name' => 'Romanne',
            'last_name' => 'Conti',
            'phone' => '081839188174',
            'gender' => 'Unknown',
            'salary' => 999999999,
            'job_position' => 'Magician',
            'rating' => 2.1,
            'country' => 'Britannia',
            'province' => 'Lionnes',
            'city' => 'London'
        ];
        $this->put('/api/user/'. $user->id .'?token='.$token, $data)
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'message' => 'User updated successfully'
            ]);
    }

    public function testShouldFailedDeleteUser() {
        $user = User::latest()->first();
        $token = JWTAuth::fromUser($user);
        $this->delete('/api/user/'. $user->id .'?token='.$token)
            ->seeStatusCode(400)
            ->seeJsonEquals(['message' => 'Failed to delete user']);
    }

    // public function testShouldDeleteAUser() {
    //     $user = User::factory()->create();
    //     $address = Address::factory()->create();
    //     $employee = Employee::factory()->create();

    //     $user->employee_id = $employee->id;
    //     $user->is_login = true;
    //     $employee->user_id = $user->id;
    //     $employee->address_id = $address->id;

    //     $user->save();
    //     $employee->save();
    //     $address->save();

    //     $token = JWTAuth::fromUser($user);

    //     $this->delete('/api/user/'.strval($user->id).'?token='.$token)
    //         ->seeStatusCode(200)
    //         ->seeJsonEquals(['message' => 'User deleted successfully']);
    // }
}
