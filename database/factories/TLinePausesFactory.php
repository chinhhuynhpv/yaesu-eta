<?php

namespace Database\Factories;

use App\Models\TLinePauses;
use Illuminate\Database\Eloquent\Factories\Factory;

class TLinePausesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TLinePauses::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'pause_start_date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'pause_end_date' => $this->faker->dateTimeThisMonth()->format('Y-m-d')
        ];
    }
}
