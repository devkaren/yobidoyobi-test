<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Hash;
use Infrastructure\Eloquent\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => env('SWAGGER_USERNAME', 'admin@gmail.com'),
            'password' => Hash::make(env('SWAGGER_PASSWORD', 'password')),
        ];
    }
}
