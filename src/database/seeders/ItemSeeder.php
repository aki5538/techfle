<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        $items = [
            [
                'user_id' => $users->random()->id,
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'status' => '良好',
            ],
            [
                'user_id' => $users->random()->id,
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'status' => '目立った傷や汚れなし',
            ],
            [
                'user_id' => $users->random()->id,
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => null,
                'description' => '新鮮な玉ねぎ3束のセット',
                'status' => 'やや傷や汚れあり',
            ],
            [
                'user_id' => $users->random()->id,
                'name' => '革靴',
                'price' => 4000,
                'brand' => null,
                'description' => 'クラシックなデザインの革靴',
                'status' => '状態が悪い',
            ],
            [
                'user_id' => $users->random()->id,
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => null,
                'description' => '高性能なノートパソコン',
                'status' => '良好',
            ],
            [
                'user_id' => $users->random()->id,
                'name' => 'マイク',
                'price' => 8000,
                'brand' => null,
                'description' => '高音質のレコーディング用マイク',
                'status' => '目立った傷や汚れなし',
            ],
            [
                'user_id' => $users->random()->id,
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => null,
                'description' => 'おしゃれなショルダーバッグ',
                'status' => 'やや傷や汚れあり',
            ],
            [
                'user_id' => $users->random()->id,
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => null,
                'description' => '使いやすいタンブラー',
                'status' => '状態が悪い',
            ],
            [
                'user_id' => $users->random()->id,
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'status' => '良好',
            ],
            [
                'user_id' => $users->random()->id,
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => null,
                'description' => '便利なメイクアップセット',
                'status' => '目立った傷や汚れなし',
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}