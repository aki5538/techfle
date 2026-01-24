<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Address;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 購入すると購入レコードが作成される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 住所が必要（仕様書）
        Address::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '福岡県福岡市テスト',
            'building' => 'テストビル',
        ]);

        $this->actingAs($user);

        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'テスト', // Stripe 分岐を避ける
        ]);

        $response->assertRedirect(route('items.index'));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function 購入した商品は商品一覧でSoldと表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Address::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '福岡県福岡市テスト',
            'building' => 'テストビル',
        ]);

        $this->actingAs($user);

        // 購入
        $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'テスト',
        ]);

        // 商品一覧へアクセス
        $response = $this->get(route('items.index'));

        // Sold 表示を確認
        $response->assertSee('Sold');
    }

    /** @test */
    public function 購入した商品はプロフィールの購入一覧に表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Address::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '福岡県福岡市テスト',
            'building' => 'テストビル',
        ]);

        $this->actingAs($user);

        // 購入
        $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'テスト',
        ]);

        // 購入一覧へアクセス
        $response = $this->get(route('mypage.index', ['page' => 'buy']));

        // 購入した商品の名前が表示されている
        $response->assertSee($item->name);
    }

    /** @test */
    public function 購入後は商品一覧画面へ遷移する()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        Address::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '福岡県福岡市テスト',
            'building' => 'テストビル',
        ]);

        $this->actingAs($user);

        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'テスト',
        ]);

        $response->assertRedirect(route('items.index'));
    }
}