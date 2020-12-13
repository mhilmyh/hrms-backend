<?php

use App\Models\Address;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShouldReturnAllUsers()
    {
        $user = User::find(1);
        $token = JWTAuth::fromUser($user);
        $this->get('/api/user?token='.$token, [])->seeStatusCode(200)
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
            "message"]);
    }

    public function testShouldReturnUnauthorized() {
        $this->get('/api/user', [])->seeStatusCode(401)
            ->seeJsonEquals(['message' => 'Unauthorized']);
    }

    public function testShouldUpdate(){
        $user = User::factory()->create();
        $address = Address::factory()->create();
        $employee = Employee::factory()->create();

        $userId = $user->id;
        $user->employee_id = $employee->id;
        $user->is_login = true;
        $employee->user_id = $user->id;
        $employee->address_id = $address->id;

        $user->save();
        $employee->save();
        $address->save();

        $token = JWTAuth::fromUser($user);

        $params = [
            'email' => 'abab@ipbu.id',
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

        $this->put('/api/user/'.strval($user->id).'?token='.$token, $params, [])
            ->seeStatusCode(200)
            ->seeJsonEquals(['message' => 'User updated successfully']);

        $user = User::find($userId);
        $user->delete();
        $employee->delete();
        $address->delete();
    }

    public function testShouldDeleteAUser() {
        $user = User::factory()->create();
        $address = Address::factory()->create();
        $employee = Employee::factory()->create();

        $user->employee_id = $employee->id;
        $user->is_login = true;
        $employee->user_id = $user->id;
        $employee->address_id = $address->id;

        $user->save();
        $employee->save();
        $address->save();

        $token = JWTAuth::fromUser($user);

        $this->delete('/api/user/'.strval($user->id).'?token='.$token, [], [])
            ->seeStatusCode(200)
            ->seeJsonEquals(['message' => 'User deleted successfully']);
    }

    public function testShouldFailedDeleteUser() {
        $authUserId = 2;
        $user = User::find($authUserId);
        $token = JWTAuth::fromUser($user);
        $this->delete('/api/user/'.strval(0).'?token='.$token, [], [])
            ->seeStatusCode(400)
            ->seeJsonEquals(['message' => 'Failed to delete user']);
    }
}
