<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function いいねすると合計値が1増える()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // ログイン
        $this->actingAs($user);

        // いいね前は0件
        $this->assertEquals(0, $item->likes()->count());

        // いいね実行
        $this->post(route('item.like', $item->id));

        // 再取得
        $item->refresh();

        // 合計値が1になっている
        $this->assertEquals(1, $item->likes()->count());
    }

    /** @test */
    public function いいね済みアイコンがピンクに変わる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        // いいね実行
        $this->post(route('item.like', $item->id));

        // 詳細ページを再取得
        $response = $this->get('/items/' . $item->id);

        // ピンクのハート画像が表示されている
        $response->assertSee('ハートロゴ_ピンク.png');
    }

    /** @test */
    public function いいね解除すると合計値が1減る()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        // まず1回いいね
        $this->post(route('item.like', $item->id));
        $item->refresh();
        $this->assertEquals(1, $item->likes()->count());

        // もう1回押す → 解除
        $this->post(route('item.like', $item->id));
        $item->refresh();

        // 合計値が0に戻る
        $this->assertEquals(0, $item->likes()->count());
    }
}