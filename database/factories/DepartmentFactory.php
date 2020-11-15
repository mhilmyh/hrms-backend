<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Department::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $depts = ["Human-Resources", "Executives", "Manufacturing", "Marketing", "Finance", "Engineering", "Research and Developments"];
        $name = $this->faker->randomElement($depts);
        return [
            'name' => $name,
            'code' => (string) array_search($name, $depts),
            'chairman_id' => User::factory(),
            'office_id' => Office::factory()
        ];
    }
}
