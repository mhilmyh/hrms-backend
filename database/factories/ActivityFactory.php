<?php

namespace Database\Factories;

use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    function random20()
    {
        $number = "";
        for ($i = 0; $i < 19; $i++) {
            $min = ($i === 0) ? 1 : 0;
            $number .= mt_rand($min, 9);
        }
        return $number;
    }
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start_time = Carbon::createFromTimeStamp($this->faker->dateTimeBetween('-1 month', '+1 month')->getTimestamp());
        return [
            'desc' => $this->faker->sentence,
            'start_time' => $start_time->toDateTimeString(),
            'stop_time' => $start_time->addHours($this->faker->numberBetween(1, 8)),
            'timesheet_id' => $this->random20()
        ];
    }
}
