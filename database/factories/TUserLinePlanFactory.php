<?php

namespace Database\Factories;

use App\Models\TUserLinePlan;
use Illuminate\Database\Eloquent\Factories\Factory;

class TUserLinePlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TUserLinePlan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'start_date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'end_date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'option_id1' => $this->faker->numberBetween(1, 2),
            'option_id2' => $this->faker->numberBetween(1, 2),
            'option_id3' => $this->faker->numberBetween(1, 2),
            'option_id4' => $this->faker->numberBetween(1, 2),
            'option_id5' => $this->faker->numberBetween(1, 2),
            'option_id6' => $this->faker->numberBetween(1, 2),
            'option_id7' => $this->faker->numberBetween(1, 2),
            'option_id8' => $this->faker->numberBetween(1, 2),
            'option_id9' => $this->faker->numberBetween(1, 2),
            'option_id10' => $this->faker->numberBetween(1, 2),
            'automatic_update' => $this->faker->numberBetween(1, 2),
            'plan_set_end_date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'line_status' => $this->faker->numberBetween(1, 2)
        ];
    }
}
