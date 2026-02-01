<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 全商品が表示される()
    {
        $items = Item::factory()->count(3)->create();

        $response = $this->get('/');

        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    /** @test */
    public function 購入済み商品には_Sold_が表示される()
    {
        // 購入者
        $buyer = User::factory()->create();

        // 購入済み商品（user_id は出品者）
        $item = Item::factory()->create();

        // 購入済みIDリストをセッション or View に渡す必要がある
        // → あなたの実装では Controller が渡しているので、
        //    テストでは with() で渡す必要がある

        $response = $this->view('items.index', [
            'items' => collect([$item]),
            'purchasedItemIds' => [$item->id], // ← ここが重要
            'keyword' => '',
            'tab' => '',
        ]);

        $response->assertSee('Sold');
    }
    /** @test */
    public function 自分が出品した商品は表示されない()
    {
        $user = User::factory()->create();

        $myItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'MY_UNIQUE_ITEM_12345',
        ]);

        $otherItem = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/');

        $response->assertDontSee($myItem->name);
        $response->assertSee($otherItem->name);
    }
    /** @test */
    public function 未認証ユーザーでも商品一覧が表示される()
    {
        $item = \App\Models\Item::factory()->create([
            'name' => 'テスト商品',
        ]);

        $response = $this->get('/');

        $response->assertSee('テスト商品');
    }
    /** @test */
    public function マイリストタブを開くといいねした商品だけが表示される()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // いいねした商品
        $likedItem = \App\Models\Item::factory()->create([
            'name' => 'いいね商品',
        ]);

        // LikeFactory がないので直接 create()
        \App\Models\Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        // いいねしていない商品
        $otherItem = \App\Models\Item::factory()->create([
            'name' => 'その他商品',
        ]);

        // マイリストタブへアクセス
        $response = $this->get('/?tab=mylist');

        // いいねした商品は見える
        $response->assertSee('いいね商品');

        // いいねしていない商品は見えない
        $response->assertDontSee('その他商品');
    }
    /** @test */
    public function マイリストでは購入済み商品にSoldが表示される()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // 商品
        $item = \App\Models\Item::factory()->create([
            'name' => '購入済み商品',
        ]);

        // いいね
        \App\Models\Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // ★ 外部キー制約を満たすための住所
        $address = \App\Models\Address::create([
            'user_id' => $user->id,
            'postal_code' => '000-0000',
            'address' => 'テスト住所',
        ]);

        // ★ Purchase レコードを作成（最低限）
        \App\Models\Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'credit',
            'address_id' => $address->id,
            'total_price' => 1000,
        ]);

        // マイリストへアクセス
        $response = $this->get('/?tab=mylist');

        // Sold が表示される
        $response->assertSee('Sold');
    }
    /** @test */
    public function 未認証の場合マイリストには何も表示されない()
    {
        // ログインしない状態でマイリストへアクセス
        $response = $this->get('/?tab=mylist');

        // 商品カードが表示されないことを確認
        // item-card クラスは商品表示の共通クラス
        $response->assertDontSee('item-card');
    }
    /** @test */
    public function 検索状態はマイリストでも保持される()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // 検索キーワード
        $keyword = 'テスト商品';

        // ホームで検索
        $response = $this->get('/?keyword=' . $keyword);
        $response->assertStatus(200);

        // マイリストに遷移（検索キーワード付き）
        $response = $this->get('/?tab=mylist&keyword=' . $keyword);

        // 検索欄にキーワードが保持されている
        $response->assertSee($keyword);
    }
}