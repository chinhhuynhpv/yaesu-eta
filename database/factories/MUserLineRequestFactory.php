<?php

namespace Database\Factories;

use App\Models\MUserLineRequest;
use App\Models\MUserRequest;
use App\Models\MShop;
use App\Models\MUser;
use App\Models\MModel;
use App\Models\MSim;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class MUserLineRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MUserLineRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'row_num' => $this->faker->numberBetween(1, 2),
            'voip_line_id' => Str::random(20),
            'line_num' => Str::random(20),
            'request_type' => $this->faker->numberBetween(1, 2),
            'voip_id_name' => $this->faker->name(),
            'models_id' => MModel::factory()->create()->id,
            'voip_line_password' => Str::random(10), // password
            'call_type' => $this->faker->numberBetween(1, 2),
            'priority' => $this->faker->numberBetween(1, 10),
            'individual' => $this->faker->numberBetween(1, 2),
            'recording' => $this->faker->numberBetween(1, 2),
            'gps' => $this->faker->numberBetween(1, 2),
            'commander' => $this->faker->numberBetween(1, 2),
            'individual_priority' => $this->faker->numberBetween(1, 2),
            'cue_reception' => $this->faker->numberBetween(1, 2),
            'start_date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'status' => $this->faker->numberBetween(1, 2),
            'change_application_date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'contract_renewal_date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'memo' => $this->faker->text($maxNbChars = 200)
        ];
    }
}
