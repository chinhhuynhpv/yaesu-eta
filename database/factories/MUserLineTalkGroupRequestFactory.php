<?php

namespace Database\Factories;

use App\Models\MUserLineTalkGroupRequest;
use App\Models\MUserLineRequest;
use App\Models\MUser;
use App\Models\MShop;
use App\Models\MUserTalkGroupRequest;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class MUserLineTalkGroupRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MUserLineTalkGroupRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'row_num' =>  $this->faker->numberBetween(1, 2),
            'line_num' => Str::random(10),
            'request_type' => $this->faker->numberBetween(1, 2),
            'name' => $this->faker->name(),
        ];
    }
}
