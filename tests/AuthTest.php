<?php


use Tymon\JWTAuth\Facades\JWTAuth;
use Faker\Factory;
use App\Models\User;

class AuthTest extends TestCase
{
    public function testShouldRegisterSuccess()
    {
        $faker = Factory::create();
        $birthday = $faker->dateTimeBetween('-90 years', '-15years');
        $data = [
            'email' => 'user@example.com',
            'password' => '1234567890',

            'first_name' => $faker->firstNameMale,
            'mid_name' => $faker->firstNameFemale,
            'last_name' => $faker->lastName,
            'phone' => '0832748139841',
            'gender' => 'Male',
            'birthday' => $birthday->format('Y-m-d'),

            'country' => $faker->country,
            'province' => $faker->state,
            'city' => $faker->city,
            'postal_code' => '12193',
            'street' => $faker->streetAddress,
        ];

        $this->post('/api/auth/register/', $data)
            ->seeStatusCode(201)
            ->seeJsonEquals([
                'message' => 'Successfully register user'
            ]);
    }

    public function testLoginShouldSuccess()
    {
        $data = [
            'email' => 'user@example.com',
            'password' => '1234567890' 
        ];

        $this->post('/api/auth/login', $data)
            ->seeStatusCode(200)
            ->seeJsonStructure(['token', 'message']);
    }

    public function testLoginShouldNotFound()
    {
        $data = [
            'email' => 'wrong@example.com',
            'password' => '1234567890' 
        ];

        $this->post('/api/auth/login', $data)
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'message' => 'User not found'
            ]);
    }

    public function testLoginShouldFailPassNotMatch()
    {
        $data = [
            'email' => 'user@example.com',
            'password' => '0123456789'
        ];

        $this->post('/api/auth/login', $data)
            ->seeStatusCode(400)
            ->seeJsonEquals([
                'message' => 'Password does not match',
            ]);
    }

    public function testShouldGetUserSuccess()
    {
        $user = User::latest()->first();
        $token = JWTAuth::fromUser($user);
        $this->get('/api/auth/user?token=' . $token)
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
                            "full_address",
                        ],
                        "supervisor",
                        "department",
                        "image",
                    ],
                ],
                'message',
            ]);
    }

    public function testShouldGetUserFailed()
    {
        $token = "this_is_not_valid_token";
        $this->get('/api/auth/user?token=' . $token)
            ->seeStatusCode(401);
    }

    public function testShouldLogoutSuccess()
    {
        $user = User::latest()->first();
        $token = JWTAuth::fromUser($user);
        $this->post('/api/auth/logout?token=' . $token)
            ->seeStatusCode(200);

        $user = User::where('email', $user->email)->first();
        $this->assertTrue(
            $user->is_login === false,
            'User should logout'
        );
    }

    public function testShouldLogoutFail()
    {
        $user = User::latest()->first();
        $user->is_login = true;
        $user->save();

        $token = "this_is_not_valid_token";
        $this->post('/api/auth/logout?token=' . $token)
            ->seeStatusCode(401);

        $user = User::latest()->first();
        $this->assertTrue(
            $user->is_login === true,
            'User should login'
        );
    }
}
