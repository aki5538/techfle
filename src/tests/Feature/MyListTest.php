<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function いいねした商品だけが表示されている()
    {
        $user = User::factory()->create();

        // いいねした商品（名前を固定）
        $likedItem = Item::factory()->create([
            'name' => 'いいね商品',
        ]);

        // いいねしていない商品（名前を固定）
        $otherItem = Item::factory()->create([
            'name' => 'その他商品',
        ]);

        $this->actingAs($user);

        // 仕様書に従い「いいねした商品だけ」を items に渡す
        $response = $this->view('items.index', [
            'items' => collect([$likedItem]),
            'likedItemIds' => [$likedItem->id],
            'purchasedItemIds' => [],
            'keyword' => '',
            'tab' => 'mylist',
        ]);

        $response->assertSee($likedItem->name);
        $response->assertDontSee($otherItem->name);
    }

    /** @test */
    public function 購入した商品は_Sold_と表示される()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'name' => '購入商品',
        ]);

        $this->actingAs($user);

        // 仕様書に従い「購入済み商品」を purchasedItemIds に入れる
        $response = $this->view('items.index', [
            'items' => collect([$item]),
            'likedItemIds' => [$item->id],
            'purchasedItemIds' => [$item->id],
            'keyword' => '',
            'tab' => 'mylist',
        ]);

        $response->assertSee('Sold');
    }

    /** @test */
    public function 未認証の場合は何も表示されない()
    {
        $item = Item::factory()->create([
            'name' => '未認証商品',
        ]);

        // 未ログイン → 仕様書どおり items は空になる前提
        $response = $this->view('items.index', [
            'items' => collect([]),
            'likedItemIds' => [],
            'purchasedItemIds' => [],
            'keyword' => '',
            'tab' => 'mylist',
        ]);

        $response->assertDontSee($item->name);
    }
}