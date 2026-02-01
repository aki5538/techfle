<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        Notification::assertSentTo($user, VerifyEmail::class);

        $response->assertRedirect(route('verification.notice'));
    }

    /** @test */
    public function 認証誘導画面から認証リンクに遷移できる()
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user);

        $response = $this->get(route('verification.notice'));
        $response->assertStatus(200);

        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->get($verifyUrl);

        $response->assertRedirect('/mypage/profile');
    }

    /** @test */
    public function 認証メールを再送できる()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $this->actingAs($user);

        $response = $this->post(route('verification.send'));

        Notification::assertSentTo($user, VerifyEmail::class);

        $response->assertStatus(302);
    }
}