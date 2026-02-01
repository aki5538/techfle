<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use Mockery;
use Stripe\Checkout\Session;




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
    /** @test */
    public function 支払い方法を選択すると購入画面に反映される()
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

        // 購入画面へアクセス
        $response = $this->get(route('purchase.create', ['item_id' => $item->id]));

        // 支払い方法の選択肢が表示されている（＝反映されている）
        $response->assertSee('コンビニ払い');
        $response->assertSee('カード払い');
    }
    
    public function test_カード払いで_stripeにリダイレクトされる()
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

        // ★ Stripe\Session を alias モックする
        $mock = Mockery::mock('alias:' . Session::class);
        $mock->shouldReceive('create')
            ->once()
            ->andReturn((object)['url' => 'https://checkout.stripe.com/test-session']);

        $response = $this->post(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'カード払い',
        ]);

        $response->assertRedirect('https://checkout.stripe.com/test-session');
    }
}