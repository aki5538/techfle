<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        // 部分一致する商品
        $item1 = Item::factory()->create(['name' => '赤い靴']);
        // 部分一致しない商品
        $item2 = Item::factory()->create(['name' => '青い帽子']);

        // 「赤」で検索
        $response = $this->get('/?keyword=赤');

        // 部分一致する商品は表示
        $response->assertSee($item1->name);
        // 部分一致しない商品は表示されない
        $response->assertDontSee($item2->name);
    }

    /** @test */
    public function 検索状態がマイリストでも保持されている()
    {
        // 検索キーワード
        $keyword = '靴';

        // マイリストページに遷移したときも keyword が保持されていること
        $response = $this->get('/?tab=mylist&keyword=' . $keyword);

        $response->assertSee($keyword);
    }
}