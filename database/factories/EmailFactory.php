<?php

namespace Database\Factories;

use App\Models\Email;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailFactory extends Factory
{
    function random20() {
        $number = "";
        for($i=0; $i<19; $i++) {
          $min = ($i == 0) ? 1:0;
          $number .= mt_rand($min,9);
        }
        return $number;
    }
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Email::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'message' => $this->faker->paragraph,
            'sender_id' => $this->random20(),
            'receiver_id' => $this->random20()
        ];
    }
}
