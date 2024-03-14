<?php

namespace Database\Factories;

use App\Models\MModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'class' => $this->faker->numberBetween(1, 4),
            'management_number' => Str::random(20),
            'product_name' => Str::random(10),
            'soft_hard' => $this->faker->numberBetween(1, 2),
            'status' => $this->faker->numberBetween(1, 2),
            'start_date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'end_date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'sport_end_date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'voice' => $this->faker->numberBetween(0, 1),
            'gps' => $this->faker->numberBetween(0, 1),
            'camera' => $this->faker->numberBetween(0, 1),
            'base' => $this->faker->numberBetween(0, 1),
            'wifi' => $this->faker->numberBetween(0, 1),
            'sim_slot' => Str::random(3),
            'standard_price' => $this->faker->unique(true)->numberBetween(1, 3000),
        ];
    }
}
