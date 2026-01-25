<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品一覧テスト①: ログイン前に全商品を取得できる
     */
    public function test_unauthenticated_user_can_view_all_products()
    {
        // テストデータ作成：複数の商品を作成
        $products = Item::factory(5)->create();

        // 商品一覧ページにアクセス
        $response = $this->get('/index');

        // ページが正常に表示されているか確認
        $response->assertStatus(200);

        // 作成したすべての商品が表示されているか確認
        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    }

    /**
     * 商品一覧テスト②a: ログイン前に購入済み商品に「Sold」ラベルが表示される
     */
    public function test_unauthenticated_user_sees_sold_label_on_purchased_products()
    {
        // 購入済み商品と未購入商品を作成
        $soldProduct = Item::factory()->create(['is_sold' => true]);
        $availableProduct = Item::factory()->create(['is_sold' => false]);

        // 商品一覧ページにアクセス
        $response = $this->get('/index');

        // ページが正常に表示されているか確認
        $response->assertStatus(200);

        // 購入済み商品に「Sold」ラベルが表示されているか確認
        $response->assertSee('Sold');

        // 両方の商品名が表示されているか確認
        $response->assertSee($soldProduct->name);
        $response->assertSee($availableProduct->name);
    }

    /**
     * 商品一覧テスト②b: ログイン済みユーザーが購入済み商品に「Sold」ラベルが表示される
     */
    public function test_authenticated_user_sees_sold_label_on_purchased_products()
    {
        // ログイン済みのユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // 購入済み商品と未購入商品を作成（ユーザーの自分の商品ではない）
        $soldProduct = Item::factory()->create(['is_sold' => true]);
        $availableProduct = Item::factory()->create(['is_sold' => false]);

        // ユーザーでログイン
        $this->actingAs($user);

        // 商品一覧ページにアクセス
        $response = $this->get('/index');

        // ページが正常に表示されているか確認
        $response->assertStatus(200);

        // 購入済み商品に「Sold」ラベルが表示されているか確認
        $response->assertSee('Sold');

        // 両方の商品名が表示されているか確認
        $response->assertSee($soldProduct->name);
        $response->assertSee($availableProduct->name);
    }

    /**
     * 商品一覧テスト③: ログイン済みユーザーが自分が出品した商品は表示されない
     */
    public function test_logged_in_user_cannot_see_own_products()
    {
        // ログイン済みのユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // ユーザーが出品した商品を作成
        $userProduct = Item::factory()->create(['user_id' => $user->id]);

        // 他のユーザーが出品した商品を作成
        $otherProducts = Item::factory(5)->create();

        // ユーザーでログイン
        $this->actingAs($user);

        // 商品一覧ページにアクセス
        $response = $this->get('/index');

        // ページが正常に表示されているか確認
        $response->assertStatus(200);

        // 他のユーザーの商品が表示されているか確認
        foreach ($otherProducts as $product) {
            $response->assertSee($product->name);
        }

        // ユーザー自身の商品は表示されていないか確認
        $response->assertDontSee($userProduct->name);
    }

    // ===== マイリスト一覧取得機能のテスト =====

    /**
     * マイリストテスト①: いいねした商品だけが表示される
     */
    public function test_authenticated_user_can_view_favorited_products()
    {
        // ログイン済みのユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // いいねした商品を作成
        $favoritedProducts = Item::factory(3)->create();
        foreach ($favoritedProducts as $product) {
            Favorite::create([
                'user_id' => $user->id,
                'item_id' => $product->id,
            ]);
        }

        // いいねしていない商品を作成
        $unfavoritedProducts = Item::factory(2)->create();

        // ユーザーでログイン
        $this->actingAs($user);

        // マイリストページにアクセス
        $response = $this->get('/index?tab=mylist');

        // ページが正常に表示されているか確認
        $response->assertStatus(200);

        // いいねした商品が表示されているか確認
        foreach ($favoritedProducts as $product) {
            $response->assertSee($product->name);
        }

        // いいねしていない商品は表示されていないか確認
        foreach ($unfavoritedProducts as $product) {
            $response->assertDontSee($product->name);
        }
    }

    /**
     * マイリストテスト②: いいねした商品の中で購入済み商品に「Sold」ラベルが表示される
     */
    public function test_favorited_purchased_products_show_sold_label()
    {
        // ログイン済みのユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // いいねした商品を作成（購入済みと未購入）
        $favoritedSoldProduct = Item::factory()->create(['is_sold' => true]);
        $favoritedAvailableProduct = Item::factory()->create(['is_sold' => false]);

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $favoritedSoldProduct->id,
        ]);
        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $favoritedAvailableProduct->id,
        ]);

        // ユーザーでログイン
        $this->actingAs($user);

        // マイリストページにアクセス
        $response = $this->get('/index?tab=mylist');

        // ページが正常に表示されているか確認
        $response->assertStatus(200);

        // いいねした商品が表示されているか確認
        $response->assertSee($favoritedSoldProduct->name);
        $response->assertSee($favoritedAvailableProduct->name);

        // 購入済み商品に「Sold」ラベルが表示されているか確認
        $response->assertSee('Sold');
    }

    /**
     * マイリストテスト③: 未認証の場合は商品が何も表示されない
     */
    public function test_unauthenticated_user_sees_no_products_on_mylist()
    {
        // 他のユーザーがいいねした商品を作成
        $products = Item::factory(5)->create();
        $otherUser = User::factory()->create();
        foreach ($products as $product) {
            Favorite::create([
                'user_id' => $otherUser->id,
                'item_id' => $product->id,
            ]);
        }

        // ログインしていない状態でマイリストページにアクセス
        $response = $this->get('/index?tab=mylist');

        // ページが正常に表示されているか確認
        $response->assertStatus(200);

        // 商品が表示されていないか確認
        foreach ($products as $product) {
            $response->assertDontSee($product->name);
        }
    }

    // ===== 商品検索機能のテスト =====

    /**
     * 商品検索テスト①: 商品名で部分一致検索ができる
     */
    public function test_can_search_products_by_keyword()
    {
        // テストデータ作成：検索キーワードを含む商品と含まない商品
        $searchableProduct1 = Item::factory()->create(['name' => 'iPhoneケース']);
        $searchableProduct2 = Item::factory()->create(['name' => 'iPhone 15']);
        $unsearchableProduct = Item::factory()->create(['name' => 'Androidケース']);

        // キーワード「iPhone」で検索
        $response = $this->get('/index?keyword=iPhone');

        // ページが正常に表示されているか確認
        $response->assertStatus(200);

        // 部分一致する商品が表示されているか確認
        $response->assertSee($searchableProduct1->name);
        $response->assertSee($searchableProduct2->name);

        // 部分一致しない商品は表示されていないか確認
        $response->assertDontSee($unsearchableProduct->name);
    }

    /**
     * 商品検索テスト②: 検索状態がマイリストでも保持されている
     */
    public function test_search_keyword_is_preserved_in_mylist()
    {
        // ログイン済みのユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // いいねした商品を作成（検索キーワードを含む・含まない）
        $favoritedSearchableProduct = Item::factory()->create(['name' => 'iPhoneケース']);
        $favoritedUnsearchableProduct = Item::factory()->create(['name' => 'Androidケース']);

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $favoritedSearchableProduct->id,
        ]);
        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $favoritedUnsearchableProduct->id,
        ]);

        // ユーザーでログイン
        $this->actingAs($user);

        // キーワード「iPhone」でマイリストを検索
        $response = $this->get('/index?tab=mylist&keyword=iPhone');

        // ページが正常に表示されているか確認
        $response->assertStatus(200);

        // 検索キーワードに部分一致するいいねした商品が表示されているか確認
        $response->assertSee($favoritedSearchableProduct->name);

        // 検索キーワードに部分一致しないいいねした商品は表示されていないか確認
        $response->assertDontSee($favoritedUnsearchableProduct->name);
    }

    // ===== 商品詳細情報取得のテスト =====

    /**
     * 商品詳細テスト①: 商品詳細ページに必要な情報がすべて表示される
     */
    public function test_product_detail_page_displays_all_required_information()
    {
        // 出品者を作成
        $seller = User::factory()->create();

        // 商品を作成
        $product = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'iPhone 15',
            'brand' => 'Apple',
            'price' => 120000,
            'description' => 'これはテスト商品です',
            'condition' => 'new',
        ]);

        // コメント者を作成
        $commenter1 = User::factory()->create();
        $commenter2 = User::factory()->create();

        // 複数のコメントを作成
        Comment::create([
            'user_id' => $commenter1->id,
            'item_id' => $product->id,
            'content' => 'これは素晴らしい商品です',
        ]);
        Comment::create([
            'user_id' => $commenter2->id,
            'item_id' => $product->id,
            'content' => '購入を検討しています',
        ]);

        // いいねを作成
        Favorite::create([
            'user_id' => User::factory()->create()->id,
            'item_id' => $product->id,
        ]);

        // 商品詳細ページにアクセス
        $response = $this->get("/item/{$product->id}");

        // ページが正常に表示されているか確認
        $response->assertStatus(200);

        // 商品情報が表示されているか確認
        $response->assertSee($product->name);
        $response->assertSee($product->brand);
        $response->assertSee(number_format($product->price));
        $response->assertSee($product->description);
        $response->assertSee('良好'); // conditionが'new'の場合

        // いいね数が表示されているか確認
        $response->assertSee('1'); // いいね数

        // コメント数が表示されているか確認
        $response->assertSee('(2)'); // コメント数

        // コメント情報が表示されているか確認
        $response->assertSee($commenter1->name);
        $response->assertSee($commenter2->name);
        $response->assertSee('これは素晴らしい商品です');
        $response->assertSee('購入を検討しています');
    }

    /**
     * 商品詳細テスト②: 複数選択されたカテゴリがすべて表示される
     */
    public function test_product_detail_page_displays_multiple_categories()
    {
        // 商品を作成
        $product = Item::factory()->create();

        // 複数のカテゴリを作成
        $categories = Category::factory(3)->create();

        // 商品にカテゴリを関連付け
        $product->categories()->attach($categories->pluck('id')->toArray());

        // 商品詳細ページにアクセス
        $response = $this->get("/item/{$product->id}");

        // ページが正常に表示されているか確認
        $response->assertStatus(200);

        // すべてのカテゴリが表示されているか確認
        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }

    // ===== 出品商品情報登録のテスト =====

    /**
     * 出品テスト①: 商品出品画面にて必要な情報が保存できること
     */
    public function test_authenticated_user_can_store_product_with_all_required_information()
    {
        // ログイン済みのユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // 複数のカテゴリを作成
        $categories = Category::factory(2)->create();

        // ユーザーでログイン
        $this->actingAs($user);

        // ダミー画像を作成
        Storage::fake('public');
        $image = UploadedFile::fake()->image('product.jpg', 300, 300);

        // 商品出品リクエストを送信
        $response = $this->post('/products', [
            'product_image' => $image,
            'name' => 'iPhone 15',
            'brand' => 'Apple',
            'description' => 'これはテスト商品です',
            'condition' => 'new',
            'price' => 120000,
            'categories' => $categories->pluck('id')->toArray(),
        ]);

        // レスポンスが成功したか確認
        $response->assertRedirect('/mypage');

        // 商品がDBに保存されているか確認
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'iPhone 15',
            'brand' => 'Apple',
            'description' => 'これはテスト商品です',
            'condition' => 'new',
            'price' => 120000,
        ]);

        // 保存された商品を取得
        $product = Item::where('user_id', $user->id)->first();

        // カテゴリが正しく関連付けられているか確認
        $this->assertEquals($product->categories()->count(), 2);
        foreach ($categories as $category) {
            $this->assertTrue($product->categories()->where('category_id', $category->id)->exists());
        }
    }
}