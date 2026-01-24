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
        ]);

        $otherItem = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/');

        $response->assertDontSee($myItem->name);
        $response->assertSee($otherItem->name);
    }
}