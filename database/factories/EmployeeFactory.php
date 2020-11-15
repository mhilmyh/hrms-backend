<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    function rand_float($st_num=0,$end_num=5,$mul=100) {
        if ($st_num > $end_num) {
            return false;
        }
        return mt_rand($st_num*$mul,$end_num*$mul)/$mul;
    }

    function delVal($del_val, $arr){
        if (($key = array_search($del_val, $arr)) !== false) {
            unset($arr[$key]);
        }
        return $arr;
    }
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        $genderNotation = ucfirst($gender)[0];
        $bd = $this->faker->dateTimeBetween('-90 years', '-15years');
        $allUsers = User::all()->pluck("id")->toArray();
        $user = $this->faker->randomElement($allUsers);
        $otherUser = $this->faker->randomElement($this->delVal($user, $allUsers));
        return [
            'first_name' => $this->faker->firstName($gender),
            'mid_name' => $this->faker->firstName($gender),
            'last_name' => $this->faker->lastName($gender),
            'phone' => $this->faker->phoneNumber,
            'gender' => $genderNotation,
            'birthday' => $bd->format("Y-m-d"),
            'salary' => mt_rand(1000000, 20000000),
            'job_position' => $this->faker->jobTitle,
            'rating' => $this->rand_float(),
            'user_id' => User::factory(),
            'image_id' => Image::factory(),
            'address_id' => Address::factory(),
            'department_id' => Department::factory(),
            'supervisor_id' => $otherUser
        ];
    }
}
