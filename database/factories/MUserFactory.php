<?php

namespace Database\Factories;

use App\Models\MUser;
use App\Models\MShop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'contractor_id' => $this->faker->unique()->numberBetween(1000000, 99999999),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('123456Ab'), // password
            'contract_name' => $this->faker->name(),
            'contract_name_kana' => $this->faker->name(),
            'representative_position' => $this->faker->name(),
            'representative_name' => $this->faker->name(),
            'representative_name_kana' => $this->faker->name(),
            'billing_department' => $this->faker->realText(30),
            'billing_manager_position' => $this->faker->realText(30),
            'billing_manager_name' => $this->faker->realText(30),
            'billing_post_number' => $this->faker->numberBetween(1000000, 99999999),
            'billing_prefectures' => $this->faker->realText(10),
            'billing_municipalities' =>$this->faker->city(),
            'billing_address' => $this->faker->address(),
            'billing_building' => $this->faker->buildingNumber(),
            'billing_tel' => $this->faker->phoneNumber(),
            'billing_fax' => $this->faker->phoneNumber(),
            'billing_email' => $this->faker->safeEmail(),
            'billing_shipping' => $this->faker->numberBetween(0, 1),
            'shipping_destination' => $this->faker->numberBetween(1, 3),
            'shipping_post_number' => $this->faker->numberBetween(1000000, 99999999),
            'shipping_prefectures' =>  $this->faker->realText(10),
            'shipping_municipalities' => $this->faker->city(),
            'shipping_address' => $this->faker->address(),
            'shipping_building'=>  $this->faker->buildingNumber(),
            'shipping_tel' => $this->faker->phoneNumber(),
            'shipping_fax' => $this->faker->numberBetween(1000000, 99999999),
            'payment_type' => $this->faker->numberBetween(1, 3),
            'bank_num' => $this->faker->numberBetween(1000000, 99999999),
            'bank_name' => $this->faker->realText(50),
            'branchi_num' => $this->faker->numberBetween(1000000, 99999999),
            'branchi_name' => $this->faker->city(),
            'deposit_type' => $this->faker->numberBetween(1, 2),
            'account_num' => $this->faker->numberBetween(1000000, 99999999),
            'account_name' =>  $this->faker->name(),
            'bank_entruster_num' => $this->faker->numberBetween(1000000, 99999999),
            'bank_customer_num' => $this->faker->numberBetween(1000000, 99999999),
            'status' => $this->faker->numberBetween(1, 2),
        ];
    }
}
