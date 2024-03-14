<?php

namespace Database\Factories;

use App\Models\MPlan;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class MPlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MPlan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'plan_num' => Str::random(10),
            'calculation_unit' => $this->faker->numberBetween(1, 2),
            'plan_name' =>  $this->faker->name(),
            'effective_date' =>  $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'expire_date' =>  $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'shop_web' => $this->faker->numberBetween(1, 2),
            'authority' => $this->faker->numberBetween(1, 2),
            'calculation_type' => $this->faker->numberBetween(1, 2),
            'usage_details_description' => $this->faker->numberBetween(0, 1),
            'incentive_description' => $this->faker->numberBetween(0, 1),
            'cancellation_limit_period' => Str::random(4),
            'usage_unit_price' =>  $this->faker->randomFloat(0,10, 200),
            'period' => Str::random(4),
            'incentive_unit_price' =>  $this->faker->randomFloat(0,10, 200),
            'incentive_unit_price2' =>  $this->faker->randomFloat(0,10, 200),
            'incentive_unit_price3' =>  $this->faker->randomFloat(0,10, 200),
        ];
    }
}
