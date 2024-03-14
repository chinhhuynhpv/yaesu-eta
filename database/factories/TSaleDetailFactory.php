<?php

namespace Database\Factories;

use App\Models\TSaleDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class TSaleDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TSaleDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'contract_date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'plan_type' => $this->faker->numberBetween(1, 4),
            'plan_id' => $this->faker->numberBetween(1, 4),
            'plan_num' => $this->faker->numberBetween(1, 10),
            'plan_name'     => $this->faker->word(),
            'unit_price' => $this->faker->randomElement([100, 200, 300]),
            'incentive_unit_price' => $this->faker->randomElement([100, 200, 300]),
            'amount' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'total_price' => $this->faker->randomElement([100, 200, 300]),
            'incentive_total_price' => $this->faker->randomElement([100, 200, 300]),
        ];
    }
}
