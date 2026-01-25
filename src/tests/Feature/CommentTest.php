<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * コメントテスト①: ログイン済みのユーザーはコメントを送信できる
     */
    public function test_authenticated_user_can_post_comment()
    {
        // ログイン済みのユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // 商品を作成
        $product = Item::factory()->create();

        // ユーザーでログイン
        $this->actingAs($user);

        // コメント送信リクエストを送信
        $response = $this->post("/products/{$product->id}/comments", [
            'content' => 'これは良い商品ですね。',
        ]);

        // リダイレクトされているか確認
        $response->assertRedirect("/item/{$product->id}");

        // DBにコメントが保存されているか確認
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $product->id,
            'content' => 'これは良い商品ですね。',
        ]);
    }

    /**
     * コメントテスト②: ログイン前のユーザーはコメントを送信できない
     */
    public function test_unauthenticated_user_cannot_post_comment()
    {
        // 商品を作成
        $product = Item::factory()->create();

        // ログインしていない状態でコメント送信リクエストを送信
        $response = $this->post("/products/{$product->id}/comments", [
            'content' => 'これは良い商品ですね。',
        ]);

        // ログインページにリダイレクトされているか確認
        $response->assertRedirect('/login');

        // DBにコメントが保存されていないか確認
        $this->assertDatabaseMissing('comments', [
            'item_id' => $product->id,
            'content' => 'これは良い商品ですね。',
        ]);
    }

    /**
     * コメントテスト③: コメントが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_comment_required_validation_error()
    {
        // ログイン済みのユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // 商品を作成
        $product = Item::factory()->create();

        // ユーザーでログイン
        $this->actingAs($user);

        // 空のコメント送信リクエストを送信
        $response = $this->post("/products/{$product->id}/comments", [
            'content' => '',
        ]);

        // バリデーションエラーが発生しているか確認
        $response->assertSessionHasErrors('content');
        $response->assertSessionHas('errors');
        $this->assertEquals(
            session('errors')->first('content'),
            'コメントを入力してください。'
        );
    }

    /**
     * コメントテスト④: コメントが255文字以上の場合、バリデーションメッセージが表示される
     */
    public function test_comment_max_length_validation_error()
    {
        // ログイン済みのユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // 商品を作成
        $product = Item::factory()->create();

        // ユーザーでログイン
        $this->actingAs($user);

        // 256文字のコメント送信リクエストを送信
        $longComment = str_repeat('あ', 256);
        $response = $this->post("/products/{$product->id}/comments", [
            'content' => $longComment,
        ]);

        // バリデーションエラーが発生しているか確認
        $response->assertSessionHasErrors('content');
        $response->assertSessionHas('errors');
        $this->assertEquals(
            session('errors')->first('content'),
            'コメントは255文字以内で入力してください。'
        );
    }
}
