<?php

namespace Database\Factories;

use App\Models\MShopUser;
use App\Models\MShop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class MShopUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MShopUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'shop_code' => $this->faker->unique()->text(30),
            'password' => Hash::make('123456Ab'), // password
            'shop_id' => MShop::factory()->create()->id,
            'shop_email' => $this->faker->safeEmail(),
        ];
    }
}
