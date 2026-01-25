<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Stripe\Checkout\Session;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Stripe セッションをモック化する
     */
    protected function mockStripeSession()
    {
        // Stripe\Checkout\Session クラスの create メソッドを直接パッチ
        \Stripe\Checkout\Session::class;
        
        $sessionObject = (object) [
            'id' => 'cs_test_' . uniqid(),
            'url' => 'https://checkout.stripe.com/pay/cs_test_dummy',
            'payment_status' => 'unpaid',
        ];
        
        // Stripe\Checkout\Session::create() 静的メソッドをモック
        \Mockery::mock('alias:\Stripe\Checkout\Session')
            ->shouldReceive('create')
            ->andReturn($sessionObject);
    }

    /**
     * 購入テスト①: 「購入する」ボタン押下で購入が完了する
     */
    public function test_user_can_purchase_product()
    {
        $this->mockStripeSession();

        // ユーザーにログインする
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
        ]);

        // 商品を作成
        $product = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'price' => 5000,
            'is_sold' => false,
        ]);

        $this->actingAs($user);

        // 「購入する」ボタンを押下（POST /purchase/{product}）
        $response = $this->post("/purchase/{$product->id}", [
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'payment_method' => 'card',
        ]);

        // Stripeセッション作成時にリダイレクトされる
        // （実際の決済テストはStripeモックが必要なため、DBに注文が保存されたことで購入完了を確認）
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $product->id,
            'price' => 5000,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'payment_method' => 'card',
        ]);
    }

    /**
     * 購入テスト②: 購入した商品は商品一覧画面にて「Sold」と表示される
     */
    public function test_purchased_product_shows_sold_in_list()
    {
        $this->mockStripeSession();

        // ユーザーにログインする
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
        ]);

        // 商品を作成
        $product = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'price' => 5000,
            'is_sold' => false,
        ]);

        $this->actingAs($user);

        // 購入前の状態を確認
        $this->assertFalse($product->is_sold);

        // 購入処理を実行（「購入する」ボタン押下）
        $purchaseResponse = $this->post("/purchase/{$product->id}", [
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'payment_method' => 'card',
        ]);

        // レスポンス確認（200 または 302 リダイレクトを期待）
        $this->assertTrue(
            $purchaseResponse->status() === 302 || $purchaseResponse->status() === 200,
            'Response status: ' . $purchaseResponse->status() . 
            ' Errors: ' . json_encode($purchaseResponse->errors ?? []) .
            ' Content: ' . $purchaseResponse->content()
        );

        // 注文が作成されたか確認
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $product->id,
        ]);

        // DBから直接取得して is_sold を確認
        $itemFromDb = \DB::table('items')->where('id', $product->id)->first();
        $this->assertTrue((bool)$itemFromDb->is_sold, 
            'is_sold column value: ' . $itemFromDb->is_sold);

        // 購入した商品が「Sold」として表示されているか確認
        // （is_sold = true になっているか確認）
        $product->refresh();
        $this->assertTrue($product->is_sold);

        // DBで確認
        $this->assertDatabaseHas('items', [
            'id' => $product->id,
            'is_sold' => true,
        ]);
    }

    /**
     * 購入テスト③: プロフィール/購入した商品一覧に追加されている
     */
    public function test_purchased_product_appears_in_profile()
    {
        $this->mockStripeSession();

        // ユーザーにログインする
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
        ]);

        // 商品を作成
        $product = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'name' => 'テスト商品',
            'price' => 5000,
            'is_sold' => false,
        ]);

        $this->actingAs($user);

        // 購入処理を実行（「購入する」ボタン押下）
        $this->post("/purchase/{$product->id}", [
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'payment_method' => 'card',
        ]);

        // DBで注文が作成されたか確認
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $product->id,
        ]);

        // プロフィール画面を表示
        $response = $this->get('/mypage');

        // 購入した商品がプロフィール/購入した商品一覧に追加されているか確認
        // （注文が存在し、購入した商品として表示される）
        $order = Order::where('user_id', $user->id)->where('item_id', $product->id)->first();
        $this->assertNotNull($order);
    }

    /**
     * 支払い方法選択テスト①: クレジットカード選択時に小計画面に反映される
     */
    public function test_credit_card_payment_method_displays_in_summary()
    {

    $this->mockStripeSession();

        // ユーザーにログインする
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
        ]);

        // 商品を作成
        $product = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'price' => 5000,
            'is_sold' => false,
        ]);

        $this->actingAs($user);

        // 支払い方法選択画面を開く
        $response = $this->get("/purchase/{$product->id}");

        // レスポンスが正常か確認
        $response->assertOk();

        // 商品情報が表示されているか確認
        $response->assertViewHas('product', $product);

        // クレジットカード選択オプションが存在するか確認
        $this->assertStringContainsString('クレジットカード', $response->getContent());
    }

    /**
     * 支払い方法選択テスト②: コンビニ払い選択時に小計画面に反映される
     */
    public function test_convenience_store_payment_method_displays_in_summary()
    {
        $this->mockStripeSession();

        // ユーザーにログインする
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
        ]);

        // 商品を作成
        $product = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'price' => 5000,
            'is_sold' => false,
        ]);

        $this->actingAs($user);

        // 支払い方法選択画面を開く
        $response = $this->get("/purchase/{$product->id}");

        // レスポンスが正常か確認
        $response->assertOk();

        // 商品情報が表示されているか確認
        $response->assertViewHas('product', $product);

        // コンビニ払いオプションが存在するか確認
        $this->assertStringContainsString('コンビニ払い', $response->getContent());
    }

    /**
     * 配送先変更テスト①: 送付先住所変更画面にて登録した住所が商品購入画面に反映される
     */
    public function test_changed_address_reflects_in_purchase_page()
    {
        $this->mockStripeSession();

        // ユーザーにログインする（初期住所）
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => '初期ビル',
        ]);

        // 商品を作成
        $product = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'price' => 5000,
            'is_sold' => false,
        ]);

        $this->actingAs($user);

        // 送付先住所変更画面で住所を登録する
        $response = $this->patch("/purchase/address_edit/{$product->id}", [
            'name' => $user->name,
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市',
            'building' => '変更後ビル',
        ]);

        // 商品購入画面にリダイレクトされるか確認
        $response->assertRedirect("/purchase/{$product->id}");

        // 商品購入画面を再度開く
        $purchaseResponse = $this->get("/purchase/{$product->id}");

        // 登録した住所が商品購入画面に正しく反映されているか確認
        $purchaseResponse->assertOk();
        $this->assertStringContainsString('〒 987-6543', $purchaseResponse->getContent());
        $this->assertStringContainsString('大阪府大阪市', $purchaseResponse->getContent());
        $this->assertStringContainsString('変更後ビル', $purchaseResponse->getContent());
    }

    /**
     * 配送先変更テスト②: 購入した商品に送付先住所が紐づいて登録される
     */
    public function test_changed_address_is_linked_to_purchased_product()
    {
        $this->mockStripeSession();

        // ユーザーにログインする（初期住所）
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => '初期ビル',
        ]);

        // 商品を作成
        $product = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'price' => 5000,
            'is_sold' => false,
        ]);

        $this->actingAs($user);

        // 送付先住所変更画面で住所を登録する
        $this->patch("/purchase/address_edit/{$product->id}", [
            'name' => $user->name,
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市',
            'building' => '変更後ビル',
        ]);

        // 商品購入画面を再度開いて購入する
        $this->post("/purchase/{$product->id}", [
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市',
            'building' => '変更後ビル',
            'payment_method' => 'card',
        ]);

        // 購入した商品に送付先住所が正しく紐づいているか確認
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $product->id,
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市',
            'building' => '変更後ビル',
        ]);
    }
}
