<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログインユーザーはコメントを送信できコメント数が増える()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        // コメント送信
        $response = $this->post(route('comment.store', ['item_id' => $item->id]), [
            'comment' => 'テストコメント',
        ]);

        $response->assertRedirect(); // back()

        // DB に保存されているか
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);

        // コメント数が1になっている
        $this->assertEquals(1, $item->comments()->count());
    }

    /** @test */
    public function 未ログインユーザーはコメントを送信できない()
    {
        $item = Item::factory()->create();

        // 未ログインで送信
        $response = $this->post(route('comment.store', ['item_id' => $item->id]), [
            'comment' => '未ログインコメント',
        ]);

        // ログイン画面へリダイレクトされる
        $response->assertRedirect('/login');

        // DB に保存されていない
        $this->assertDatabaseMissing('comments', [
            'comment' => '未ログインコメント',
        ]);
    }

    /** @test */
    public function コメント未入力の場合バリデーションエラーになる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('comment.store', ['item_id' => $item->id]), [
            'comment' => '',
        ]);

        // バリデーションエラー
        $response->assertSessionHasErrors([
            'comment' => 'コメントは必須です。',
        ]);
    }

    /** @test */
    public function コメントが255字を超えるとバリデーションエラーになる()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $longText = str_repeat('あ', 256);

        $response = $this->post(route('comment.store', ['item_id' => $item->id]), [
            'comment' => $longText,
        ]);

        // バリデーションエラー
        $response->assertSessionHasErrors([
            'comment' => '255文字以内で入力してください。',
        ]);
    }
}