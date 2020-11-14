<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->faker->locale = 'id_ID';
        return [
            'country' => $this->faker->country,
            'province' => $this->faker->state,
            'city' => $this->faker->city,
            'subdistrict' => $this->faker->citySuffix,
            'postal_code' => $this->faker->postcode,
            'street' => $this->faker->streetAddress
        ];
    }
}
