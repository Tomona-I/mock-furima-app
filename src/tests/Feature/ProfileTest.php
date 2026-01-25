<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * プロフィール情報取得テスト①: 必要な情報が取得できる
     * （プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）
     */
    public function test_profile_page_displays_all_required_information()
    {
        // ユーザーにログインする
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'name' => 'テストユーザー',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'profile_image' => 'profile_images/test.jpg',
        ]);

        // ユーザーが出品した商品を作成
        $listedProduct1 = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品1',
        ]);
        $listedProduct2 = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品2',
        ]);

        // ユーザーが購入した商品を作成
        $purchasedProduct1 = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'name' => '購入商品1',
        ]);
        $purchasedProduct2 = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'name' => '購入商品2',
        ]);

        // 購入商品に対する注文レコードを作成
        Order::create([
            'user_id' => $user->id,
            'item_id' => $purchasedProduct1->id,
            'price' => 5000,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'payment_method' => 'card',
        ]);
        Order::create([
            'user_id' => $user->id,
            'item_id' => $purchasedProduct2->id,
            'price' => 3000,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'payment_method' => 'convenience',
        ]);

        $this->actingAs($user);

        // プロフィールページを開く（出品商品一覧）
        $response = $this->get('/mypage');

        // ユーザー情報が取得できているか確認
        $response->assertOk();
        $response->assertViewHas('user');
        $this->assertEquals($user->id, $response->viewData('user')->id);
        $this->assertEquals('テストユーザー', $response->viewData('user')->name);
        $this->assertEquals('profile_images/test.jpg', $response->viewData('user')->profile_image);

        // 出品した商品一覧が取得できているか確認
        $response->assertViewHas('listedProducts');
        $listedProducts = $response->viewData('listedProducts');
        $this->assertCount(2, $listedProducts);
        $this->assertTrue($listedProducts->contains('id', $listedProduct1->id));
        $this->assertTrue($listedProducts->contains('id', $listedProduct2->id));

        // 購入した商品一覧が取得できているか確認
        // （page=buy パラメータで購入商品一覧を取得）
        $responseBuy = $this->get('/mypage?page=buy');
        $responseBuy->assertViewHas('purchasedOrders');
        $purchasedOrders = $responseBuy->viewData('purchasedOrders');
        $this->assertCount(2, $purchasedOrders);
        $this->assertTrue($purchasedOrders->contains('item_id', $purchasedProduct1->id));
        $this->assertTrue($purchasedOrders->contains('item_id', $purchasedProduct2->id));
    }

    /**
     * ユーザー情報変更テスト①: 各項目の初期値が正しく表示されている
     * （プロフィール画像、ユーザー名、郵便番号、住所）
     */
    public function test_profile_edit_page_displays_initial_values()
    {
        // ユーザーにログインする
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'name' => 'テストユーザー',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'profile_image' => 'profile_images/test.jpg',
        ]);

        $this->actingAs($user);

        // プロフィール編集画面を開く
        $response = $this->get('/profile_edit');

        // レスポンスが正常か確認
        $response->assertOk();

        // ユーザー情報がビューに渡されているか確認
        $response->assertViewHas('user');
        $viewUser = $response->viewData('user');

        // 各項目の初期値が正しく表示されているか確認
        $this->assertEquals('テストユーザー', $viewUser->name);
        $this->assertEquals('123-4567', $viewUser->postal_code);
        $this->assertEquals('東京都渋谷区', $viewUser->address);
        $this->assertEquals('テストビル', $viewUser->building);
        $this->assertEquals('profile_images/test.jpg', $viewUser->profile_image);
    }
}
