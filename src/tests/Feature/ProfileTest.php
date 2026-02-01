<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use App\Models\Purchase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function プロフィール画面にユーザー情報が表示される()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'profile/test.png',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('mypage.index'));

        // プロフィール画像
        $response->assertSee('profile/test.png');

        // ユーザー名
        $response->assertSee('テストユーザー');
    }

    /** @test */
    public function 出品した商品一覧が表示される()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品A',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('mypage.index', ['page' => 'sell']));

        $response->assertSee('出品商品A');
    }

    /** @test */
    public function 購入した商品一覧が表示される()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'name' => '購入商品B',
        ]);

        // 購入に必要な住所を作成
        $address = Address::create([
            'user_id' => $user->id,
            'postal_code' => '111-1111',
            'address' => 'テスト住所',
            'building' => 'テストビル',
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'address_id' => $address->id,
            'payment_method' => 'テスト',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('mypage.index', ['page' => 'buy']));

        $response->assertSee('購入商品B');
    }

    /** @test */
    public function プロフィール編集画面に遷移できる()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('mypage.profile'));

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }
}
