<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Timesheet;
use App\Models\User;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimesheetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Timesheet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'is_approved' => $this->faker->boolean,
            'user_id' => User::all()->pluck('id')->random(),
        ];
    }
}
