<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            "name" => "Testing Admin",
            "email" => "admin@sdui.com",
            "email_verified_at" => now(),
            "password" => Hash::make("password")
        ]);
    }
}
