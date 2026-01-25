<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use App\Models\Purchase;

class UserProfileInfoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function プロフィールページで必要な情報が表示される()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'profile/test.jpg',
        ]);

        // 出品した商品
        $sellItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品A',
        ]);

        // 購入した商品
        $buyItem = Item::factory()->create([
            'name' => '購入商品B',
        ]);

        // 住所（購入に必要）
        $address = Address::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '福岡県中間市テスト',
            'building' => 'テストビル',
        ]);

        // 購入レコード
        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $buyItem->id,
            'address_id' => $address->id, // ← 修正ポイント
            'payment_method' => 'テスト',
        ]);

        $this->actingAs($user);

        // 出品一覧（デフォルト page=sell）
        $response = $this->get(route('mypage.index'));

        // プロフィール画像
        $response->assertSee('profile/test.jpg');

        // ユーザー名
        $response->assertSee('テストユーザー');

        // 出品した商品
        $response->assertSee('出品商品A');

        // 購入した商品（page=buy に切り替え）
        $responseBuy = $this->get(route('mypage.index', ['page' => 'buy']));
        $responseBuy->assertSee('購入商品B');
    }
}