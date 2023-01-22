<?php

namespace Modules\Auth\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Auth\Entities\User::class;

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
            'username' => $this->faker->unique()->userName,
            'email_verified_at' => now(),
            'phone'=>$this->faker->unique()->phoneNumber,
            'status' => 1,
            "usergroup_id" => 1,
            'password' => bcrypt("123456"), // password
            'remember_token' => Str::random(10),
        ];
    }
}

