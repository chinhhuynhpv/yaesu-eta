<?php

namespace Database\Factories;

use App\Models\MUserTalkGroup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MUserTalkGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MUserTalkGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'voip_group_id' => Str::random(10),
            'name' => Str::random(10),
            'priority' => $this->faker->numberBetween(1, 10),
            'member_view' => $this->faker->numberBetween(1, 2),
            'group_responsible_person' => Str::random(10),
            'status'=> Str::random(3),
        ];
    }
}
