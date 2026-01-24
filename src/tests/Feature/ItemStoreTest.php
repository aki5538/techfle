<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;

class ItemStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 出品画面で入力した情報が正しく保存される()
    {
        $user = User::factory()->create();

        Category::insert([
            ['id' => 1, 'name' => 'ファッション'],
            ['id' => 2, 'name' => '家電'],
        ]);

        $this->actingAs($user);

        $response = $this->post(route('sell.store'), [
            'name'        => 'テスト商品',
            'brand'       => 'テストブランド',
            'price'       => 5000,
            'description' => 'テスト説明文',
            'status'      => 'like-new',
            'categories'  => ['1', '2'], // sync() に渡される
        ]);

        // DB に商品が保存されているか
        $this->assertDatabaseHas('items', [
            'user_id'     => $user->id,
            'name'        => 'テスト商品',
            'brand'       => 'テストブランド',
            'price'       => 5000,
            'description' => 'テスト説明文',
            'status'      => 'like-new',
        ]);

        // カテゴリの紐づけ確認（item_category 中間テーブル）
        $item = Item::first();

        $this->assertDatabaseHas('item_category', [
            'item_id'     => $item->id,
            'category_id' => 1,
        ]);

        $this->assertDatabaseHas('item_category', [
            'item_id'     => $item->id,
            'category_id' => 2,
        ]);

        // リダイレクト先の確認
        $response->assertRedirect(route('items.show', ['item_id' => $item->id]));
    }
}