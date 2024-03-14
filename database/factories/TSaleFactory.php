<?php

namespace Database\Factories;

use App\Models\TSale;
use Illuminate\Database\Eloquent\Factories\Factory;

class TSaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TSale::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sales_ym' => sprintf("%04d%02d",
                                    $this->faker->numberBetween(2020, 2021),
                                    $this->faker->numberBetween(1, 12)),
            'sales_total_price' => $this->faker->numberBetween(1200, 2000) * 10,
            'incentive_total_price' => 11000 * $this->faker->numberBetween(0, 1),
        ];
    }
}
