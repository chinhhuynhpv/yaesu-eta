<?php

namespace Database\Factories;

use App\Models\MUserTalkGroupRequest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\MUserRequest;

class MUserTalkGroupRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MUserTalkGroupRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'voip_group_id' => $this->faker->unique()->numberBetween(1000000, 9999999),
            'row_num' => $this->faker->numberBetween(1, 2),
            'request_type' => $this->faker->numberBetween(1, 2),
            'name' => $this->faker->name(),
            'priority' => $this->faker->numberBetween(1, 10),
            'member_view' => $this->faker->numberBetween(1, 2),
            'group_responsible_person' => Str::random(10),
            'status'=> Str::random(3)
        ];
    }
}
