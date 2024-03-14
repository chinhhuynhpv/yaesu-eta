<?php

namespace Database\Factories;

use App\Models\MAdminUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class MAdminUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MAdminUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('123456Ab'), // password
        ];
    }

    /**
     * Set the role of user.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function setRole($role)
    {
        return $this->state(function (array $attributes)  use ($role) {
            return [
                'is_admin' => $role,
            ];
        });
    }
}
