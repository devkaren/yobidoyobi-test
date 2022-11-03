<?php

namespace Database\Seeders;

use Infrastructure\Eloquent\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::count() > 0) {
            $this->command->info("Skipping 'UserSeeder' because there is at least one dataset already present.");
            return;
        }

        User::create([
            'email' => env('SWAGGER_USERNAME', 'admin@gmail.com'),
            'password' => Hash::make(env('SWAGGER_PASSWORD', 'password')),
        ]);
    }
}
