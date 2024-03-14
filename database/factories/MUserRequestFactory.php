<?php

namespace Database\Factories;

use App\Models\MUserRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class MUserRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MUserRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'add_flg' => $this->faker->numberBetween(0, 1),
            'modify_flg' => $this->faker->numberBetween(0, 1),
            'pause_restart_flg' => $this->faker->numberBetween(0, 1),
            'discontinued_flg' => $this->faker->numberBetween(0, 1),
            'status' => $this->faker->numberBetween(1, 4),
            'request_date' => $this->faker->date()
        ];
    }
}
