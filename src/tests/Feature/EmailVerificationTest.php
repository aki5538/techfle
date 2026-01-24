<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 会員登録後に認証メールが送信される()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'postal_code' => '1234567',
        ]);

        $user = User::first();

        // 認証メールが送信されたことを確認
        Notification::assertSentTo($user, VerifyEmail::class);

        // 誘導画面へリダイレクト
        $response->assertRedirect(route('verification.notice'));
    }

    /** @test */
    public function 認証誘導画面から認証リンクに遷移できる()
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user);

        // 誘導画面を表示
        $response = $this->get(route('verification.notice'));
        $response->assertStatus(200);

        // 誘導画面の「認証はこちらから」リンクを再現
        $verifyUrl = URL::signedRoute('verification.verify', [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]);

        // 認証リンクへアクセス
        $response = $this->get($verifyUrl);

        // 認証完了後はプロフィール設定画面へ遷移
        $response->assertRedirect('/mypage/profile');
    }

    /** @test */
    public function 認証メールを再送できる()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $this->actingAs($user);

        // 再送リクエスト
        $response = $this->post(route('verification.send'));

        // メールが再送されていること
        Notification::assertSentTo($user, VerifyEmail::class);

        // 元の画面に戻る
        $response->assertStatus(302);
    }
}