<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShouldRegisterSuccess() {
        $faker = Faker\Factory::create();
        $bd = $faker->dateTimeBetween('-90 years', '-15years');
        $params = [
            'email' => $faker->unique()->safeEmail,
            'password' => '1234567890',

            'first_name' => $faker->firstNameMale,
            'mid_name' => $faker->firstNameFemale,
            'last_name' => $faker->lastName,
            'phone' => '0832748139841',
            'gender' => 'Male',
            'birthday' => $bd->format('Y-m-d'),

            'country' => $faker->country,
            'province' => $faker->state,
            'city' => $faker->city,
            'postal_code' => '12193',
            'street' => $faker->streetAddress
        ];
        echo json_encode($params);
        $this->post('/api/auth/register/', $params, [])
            ->seeStatusCode(201)
            ->seeJsonStructure([
                'user' => [
                    "is_admin",
                    "is_login",
                    "email",
                    "created_at",
                    "updated_at",
                    "id",
                    "employee_id",
                ],
                'message'
            ]);
    }

    public function testLoginShouldSuccess()
    {
        $userId = User::all()->pluck('id')->random();
        $user1 = User::find($userId);
        $params = [
            'email' => $user1->email,
            'password' => "1234567890"
        ];
        $this->post('/api/auth/login', $params, [])
            ->seeStatusCode(200)
            ->seeJsonStructure([
                'token',
                'user' => [
                    "id",
                    "email",
                    "is_admin",
                    "is_login",
                    "employee_id",
                    "created_at",
                    "updated_at",
                ],
                'message'
            ]);
    }

    public function testLoginShouldNotFound()
    {
        // Isikan saja dengan yang tidak ada di db
        $params = [
            'email' => 'abab@icu.id',
            'password' => '1234567890'
        ];
        $this->post('/api/auth/login', $params, [])
            ->seeStatusCode(404);
    }

    public function testLoginShouldFailNotMatchingPass()
    {
        // Isikan saja dengan yang tidak ada di db
        $userId = User::all()->pluck('id')->random();
        $user1 = User::find($userId);
        $params = [
            'email' => $user1->email,
            'password' => '1234139103'
        ];
        $this->post('/api/auth/login', $params, [])
            ->seeStatusCode(400)
            ->seeJsonEquals([
                'message' => 'Password does not match'
            ]);
    }

    public function testShouldGetUserSuccess() {
        $user1 = User::find(1);
        $user1->is_login = true;
        $user1->is_admin = false;
        $user1->save();
        $token = JWTAuth::fromUser($user1);
        $this->get('/api/auth/user?token='.$token, [])
            ->seeStatusCode(200)
            ->seeJsonStructure([
                'user' => [
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
                ],
                'message'
            ]);
    }

    public function testShouldGetUserFailed() {
        $user1 = User::find(1);
        $user1->is_login = true;
        $user1->is_admin = false;
        $user1->save();
        $token = "blajblahblahblahbllah";
        $this->get('/api/auth/user?token='.$token, [])
            ->seeStatusCode(401);
        $user1->is_login = false;
        $user1->save();
    }

    public function testShouldLogoutSuccess(){
        $user1 = User::find(1);
        $user1->is_login = true;
        $user1->is_admin = false;
        $user1->save();
        $token = JWTAuth::fromUser($user1);
        $this->post('/api/auth/logout?token='.$token, [])
            ->seeStatusCode(200);
        $user1->is_login = false;
        $user1->save();
    }

    public function testShouldLogoutFail(){
        $user1 = User::find(1);
        $user1->is_login = true;
        $user1->is_admin = false;
        $user1->save();
        $token = "bakcaoncqoiuecoqpecmqpdl";
        $this->post('/api/auth/logout?token='.$token, [])
            ->seeStatusCode(401);
        $user1->is_login = false;
        $user1->save();
    }
}
