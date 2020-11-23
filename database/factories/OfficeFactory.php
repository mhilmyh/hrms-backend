<?php

namespace Database\Factories;

use App\Models\Office;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfficeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Office::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start_time = Carbon::createFromTimeStamp($this->faker->dateTimeBetween('-1 day', '-6 hours')->getTimestamp());
        return [
            'name' => $this->faker->company,
            'opening_time' => $start_time->format('H:i'),
            'closing_time' => $start_time->addHours($this->faker->numberBetween(6, 10))->format('H:i'),
            'building' => "BuildingName",
            'is_branch' => true,
            'address_id' => 1
        ];
    }
}
