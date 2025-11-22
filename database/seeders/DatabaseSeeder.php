<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'phone' => '628111111111',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create regular user
        User::create([
            'name' => 'User',
            'username' => 'customer',
            'email' => 'user@example.com',
            'phone' => '628122222222',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->call([
            GameAccountFieldSeeder::class,
            VipResellerSettingSeeder::class,
        ]);
    }
}
