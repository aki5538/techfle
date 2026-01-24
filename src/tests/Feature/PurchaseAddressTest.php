<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use App\Models\Purchase;

class PurchaseAddressTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 住所変更画面で登録した住所が購入画面に反映される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 初期住所
        Address::create([
            'user_id' => $user->id,
            'postal_code' => '000-0000',
            'address' => '初期住所',
            'building' => '初期ビル',
        ]);

        $this->actingAs($user);

        // 住所を更新
        $this->put(route('purchase.address.update', ['item_id' => $item->id]), [
            'postal_code' => '123-4567',
            'address' => '福岡県福岡市テスト',
            'building' => 'テストビル',
        ]);

        // 購入画面を再度開く
        $response = $this->get(route('purchase.create', ['item_id' => $item->id]));

        // 更新した住所が表示されている
        $response->assertSee('123-4567');
        $response->assertSee('福岡県福岡市テスト');
        $response->assertSee('テストビル');
    }

    /** @test */
    public function 購入した商品に送付先住所が紐づいて登録される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 更新後の住所
        $address = Address::create([
            'user_id' => $user->id,
            'postal_code' => '987-6543',
            'address' => '東京都港区テスト',
            'building' => 'テストマンション',
        ]);

        $this->actingAs($user);

        // 購入処理（Stripe を避けるため payment_method は "テスト"）
        $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'テスト',
        ]);

        // 購入レコードが正しい住所を保持しているか確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'address_id' => $address->id,
        ]);
    }
}