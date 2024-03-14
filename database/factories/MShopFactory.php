<?php

namespace Database\Factories;

use App\Models\MShop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MShopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MShop::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'serial_number' => $this->faker->randomDigitNotNull(),
            'name' => $this->faker->firstName(),
            'code' => Str::random(3),
            'postal_cd' => $this->faker->postcode(),
            'prefecture' => Str::random(10),
            'city' => Str::random(10),
            'address' => Str::random(10),
            'building_name' => Str::random(10),
            'tel_number' => $this->faker->e164PhoneNumber(),
            'fax_number' => $this->faker->e164PhoneNumber(),
            'email' => $this->faker->safeEmail(),
            'incentive_flg' => $this->faker->numberBetween(0, 1),
            'incentive_type' => $this->faker->numberBetween(1, 3),
            'sap_supplier_num' => $this->faker->numberBetween(1000000, 9999999),
        ];
    }
}
