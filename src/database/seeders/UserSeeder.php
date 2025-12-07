<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => '山田花子',
            'email' => 'hanako@example.com',
            'password' => Hash::make('password123'), // テスト用パスワード
            'postal_code' => '123-4567',
            'address' => '福岡県北九州市テスト町1-2-3',
            'building' => 'テストビル101',
            'profile_image' => null,
        ]);
    }
}
