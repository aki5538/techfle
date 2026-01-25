<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserProfileEditInitialValueTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function プロフィール編集画面に初期値が正しく表示される()
    {
        $user = User::factory()->create([
            'name' => '初期ユーザー名',
            'profile_image' => 'profile/test.jpg',
            'postal_code' => '123-4567',
            'address' => '福岡県中間市テスト',
            'building' => 'テストビル',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('mypage.profile'));

        // プロフィール画像
        $response->assertSee('profile/test.jpg');

        // ユーザー名
        $response->assertSee('初期ユーザー名');

        // 郵便番号
        $response->assertSee('123-4567');

        // 住所
        $response->assertSee('福岡県中間市テスト');

        // 建物名
        $response->assertSee('テストビル');
    }
}