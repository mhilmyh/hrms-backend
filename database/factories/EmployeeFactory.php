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
    function rand_float($st_num = 0, $end_num = 5, $mul = 100)
    {
        if ($st_num > $end_num) {
            return false;
        }
        return mt_rand($st_num * $mul, $end_num * $mul) / $mul;
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
        $gender = $this->faker->randomElement(['Male', 'Female']);
        $bd = $this->faker->dateTimeBetween('-90 years', '-15years');
        $image = Image::all()->pluck("id")->random();

        return [
            'first_name' => $this->faker->firstName($gender),
            'mid_name' => $this->faker->firstName($gender),
            'last_name' => $this->faker->lastName($gender),
            'phone' => $this->faker->phoneNumber,
            'gender' => $gender,
            'birthday' => $bd->format("Y-m-d"),
            'salary' => mt_rand(1000000, 20000000),
            'job_position' => $this->faker->jobTitle,
            'rating' => $this->rand_float(),
            'user_id' => 1,
            'image_id' => $image,
            'address_id' => Address::all()->pluck("id")->random(),
            'department_id' => Department::all()->pluck("id")->random(),
            'supervisor_id' => 2
        ];
    }
}
