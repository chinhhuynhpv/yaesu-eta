<?php

namespace Database\Factories;

use App\Models\MSim;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MSimFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MSim::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sim_num' => $this->faker->numberBetween(1000000, 9999999),
            'career' => Str::random(10),
            'sim_contractor' => Str::random(10),
            'status' => $this->faker->numberBetween(1, 5),
            'remark' => $this->faker->text(25),
            'sim_opening_date' => $this->faker->dateTimeThisMonth()->format('Y-m-d')
        ];
    }
}
