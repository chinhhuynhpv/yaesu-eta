<?php

namespace Database\Factories;

use App\Models\MOptionPlan;

class MOptionPlanFactory extends MPlanFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MOptionPlan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $definitions = parent::definition();

        return array_merge($definitions, [
            'discount_target1' => $this->faker->text(10),
            'discount_target2' => $this->faker->text(10),
            'discount_target3' => $this->faker->text(10),
            'discount_target4' => $this->faker->text(10),
            'discount_target5' => $this->faker->text(10),
            'option_type' => $this->faker->numberBetween(1, 2),
        ]);
    }
}
