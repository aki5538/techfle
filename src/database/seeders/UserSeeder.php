<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'postal_code' => '1234567',
            'address' => '東京都新宿区1-1-1',
            'building' => null,
            'profile_image' => null,
        ]);
    }
}
