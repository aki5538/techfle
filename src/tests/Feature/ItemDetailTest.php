<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\User;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 必要な情報が商品詳細ページに表示される()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'brand' => 'テストブランド',
            'price' => 3000,
            'description' => 'テスト説明',
            'status' => 'no-damage',
        ]);

        DB::table('comments')->insert([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'comment' => 'コメント内容テスト',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get('/items/' . $item->id);

        // 商品名
        $response->assertSee($item->name);

        // ブランド（Blade の表示形式に合わせる）
        $response->assertSee('ブランド：テストブランド');

        // 価格（Blade は ¥ + number_format）
        $response->assertSee('¥3,000');

        // 商品説明
        $response->assertSee('テスト説明');

        // コメント内容
        $response->assertSee('コメント内容テスト');

        // コメントしたユーザー名
        $response->assertSee($user->name);
    }

    /** @test */
    public function 複数カテゴリが表示されている()
    {
        $item = Item::factory()->create();

        $catA = DB::table('categories')->insertGetId([
            'name' => 'カテゴリA',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $catB = DB::table('categories')->insertGetId([
            'name' => 'カテゴリB',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('item_category')->insert([
            ['item_id' => $item->id, 'category_id' => $catA],
            ['item_id' => $item->id, 'category_id' => $catB],
        ]);

        $response = $this->get('/items/' . $item->id);

        $response->assertSee('カテゴリA');
        $response->assertSee('カテゴリB');
    }

    /** @test */
    public function 未承認ユーザーにも商品詳細が表示される()
    {
        $item = Item::factory()->create([
            'name' => '未ログインでも見える商品',
        ]);

        $response = $this->get('/items/' . $item->id);

        $response->assertSee('未ログインでも見える商品');
    }
}