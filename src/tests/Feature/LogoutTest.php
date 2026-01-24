<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログアウトができる()
    {
        // 1. ユーザーを作成してログイン
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        // 2. ログアウトボタン（POST /logout）を押す
        $response = $this->post('/logout');

        // 3. ログアウト処理が実行されていること
        $this->assertGuest();

        // 4. 遷移先は Fortify のデフォルト（/）で OK
        $response->assertRedirect('/');
    }
}