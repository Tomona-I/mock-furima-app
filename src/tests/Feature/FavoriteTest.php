<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * いいねテスト①: いいねアイコンを押下することによって、いいねした商品として登録することができる
     */
    public function test_authenticated_user_can_add_favorite()
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

        // いいねリクエストを送信
        $response = $this->post("/favorites/{$product->id}");

        // レスポンスがJSONで返されているか確認
        $response->assertStatus(200);
        $response->assertJson([
            'favorited' => true,
            'count' => 1,
        ]);

        // DBにFavoriteレコードが作成されているか確認
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $product->id,
        ]);
    }

    /**
     * いいねテスト②: 追加済みのアイコンは色が変化する
     */
    public function test_favorite_response_indicates_favorited_status()
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

        // いいねリクエストを送信
        $response = $this->post("/favorites/{$product->id}");

        // レスポンスに favorited: true が含まれているか確認
        $response->assertJson([
            'favorited' => true,
        ]);

        // 再度いいねリクエストを送信（いいね削除）
        $response = $this->post("/favorites/{$product->id}");

        // レスポンスに favorited: false が含まれているか確認
        $response->assertJson([
            'favorited' => false,
        ]);
    }

    /**
     * いいねテスト③: 再度いいねアイコンを押下することによって、いいねを解除することができる
     */
    public function test_authenticated_user_can_remove_favorite()
    {
        // ログイン済みのユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // 商品を作成
        $product = Item::factory()->create();

        // いいねを事前に作成
        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $product->id,
        ]);

        // ユーザーでログイン
        $this->actingAs($user);

        // いいね削除リクエストを送信
        $response = $this->post("/favorites/{$product->id}");

        // レスポンスがJSONで返されているか確認
        $response->assertStatus(200);
        $response->assertJson([
            'favorited' => false,
            'count' => 0,
        ]);

        // DBからFavoriteレコードが削除されているか確認
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $product->id,
        ]);
    }
}
