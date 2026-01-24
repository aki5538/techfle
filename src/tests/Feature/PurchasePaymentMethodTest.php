<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;

class PurchasePaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 支払い方法のプルダウンが表示される()
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

        $response = $this->get(route('purchase.create', ['item_id' => $item->id]));

        // 支払い方法のタイトル
        $response->assertSee('支払い方法');

        // プルダウンの選択肢
        $response->assertSee('コンビニ払い');
        $response->assertSee('カード払い');

        // 小計欄の初期表示
        $response->assertSee('選択してください');
    }
}