<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    // ===== 会員登録機能のテスト =====

    /**
     * テスト①: 名前が入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_register_without_name_shows_validation_error()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('name');
        $response->assertSessionHas('errors');
        $this->assertEquals(
            session('errors')->first('name'),
            'お名前を入力してください'
        );
    }

    /**
     * テスト②: メールアドレスが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_register_without_email_shows_validation_error()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $response->assertSessionHas('errors');
        $this->assertEquals(
            session('errors')->first('email'),
            'メールアドレスを入力してください'
        );
    }

    /**
     * テスト③: パスワードが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_register_without_password_shows_validation_error()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors('password');
        $response->assertSessionHas('errors');
        $this->assertEquals(
            session('errors')->first('password'),
            'パスワードを入力してください'
        );
    }

    /**
     * テスト④: パスワードが7文字以下の場合、バリデーションメッセージが表示される
     */
    public function test_register_with_short_password_shows_validation_error()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'pass123',  // 7文字
            'password_confirmation' => 'pass123',
        ]);

        $response->assertSessionHasErrors('password');
        $response->assertSessionHas('errors');
        $this->assertEquals(
            session('errors')->first('password'),
            'パスワードは8文字以上で入力してください'
        );
    }

    /**
     * テスト⑤: パスワードが確認用パスワードが一致しない場合、バリデーションメッセージが表示される
     */
    public function test_register_with_mismatched_password_confirmation_shows_validation_error()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password456',
        ]);

        $response->assertSessionHasErrors('password');
        $response->assertSessionHas('errors');
        $this->assertEquals(
            session('errors')->first('password'),
            'パスワードと一致しません'
        );
    }

    /**
     * テスト⑥: すべての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移される
     */
    public function test_register_with_valid_data_creates_user_and_redirects()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // ユーザーがDBに登録されているか確認
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // プロフィール設定画面にリダイレクトされているか確認
        $response->assertRedirectContains('/email/verify');

        // ユーザーが認証されているか確認
        $this->assertAuthenticated();
    }

    // ===== ログイン機能のテスト =====

    /**
     * ログインテスト①: メールアドレスが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_login_without_email_shows_validation_error()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $response->assertSessionHas('errors');
        $this->assertEquals(
            session('errors')->first('email'),
            'メールアドレスを入力してください'
        );
    }

    /**
     * ログインテスト②: パスワードが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_login_without_password_shows_validation_error()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
        $response->assertSessionHas('errors');
        $this->assertEquals(
            session('errors')->first('password'),
            'パスワードを入力してください'
        );
    }

    /**
     * ログインテスト③: 入力情報が間違っている場合、バリデーションメッセージが表示される
     */
    public function test_login_with_unregistered_credentials_shows_validation_error()
    {
        $response = $this->post('/login', [
            'email' => 'unregistered@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $response->assertSessionHas('errors');
        $this->assertEquals(
            session('errors')->first('email'),
            'ログイン情報が登録されていません'
        );
    }

    /**
     * ログインテスト④: 正しい情報が入力された場合、ログイン処理が実行される
     */
    public function test_login_with_valid_credentials_logs_in_user()
    {
        // メール認証済みのユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // マイページにリダイレクトされているか確認
        $response->assertRedirect('/mypage');

        // ユーザーが認証されているか確認
        $this->assertAuthenticated();

        // 認証されているユーザーが正しいか確認
        $this->assertEquals(auth()->user()->id, $user->id);
    }

    // ===== ログアウト機能のテスト =====

    /**
     * ログアウトテスト①: ログアウトができる
     */
    public function test_user_can_logout()
    {
        // メール認証済みのユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // ユーザーでログイン
        $this->actingAs($user);

        // ユーザーが認証されていることを確認
        $this->assertAuthenticated();

        // ログアウトリクエストを送信
        $response = $this->post('/logout');

        // ログインページにリダイレクトされているか確認
        $response->assertRedirect('/login');

        // ユーザーがゲスト状態（認証解除）であることを確認
        $this->assertGuest();
    }

    // ===== メール認証機能のテスト =====

    /**
     * メール認証テスト①: 会員登録後、認証メールが送信される
     */
    public function test_email_verification_link_is_sent_after_registration()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // ユーザーが作成されたか確認
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);

        // 登録後、メール認証画面にリダイレクトされることを確認
        $response->assertRedirect('/email/verify');
    }

    /**
     * メール認証テスト②: メール認証誘導画面で「認証はこちらから」ボタンを押下するとメール認証サイト（Mailhog）に遷移する
     */
    public function test_email_verification_page_has_mailhog_button()
    {
        // メール認証済みではないユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => null,
        ]);

        // ユーザーでログイン
        $this->actingAs($user);

        // メール認証誘導画面にアクセス
        $response = $this->get('/email/verify');

        // ページが表示されているか確認
        $response->assertStatus(200);

        // 「認証はこちらから」ボタンが表示されているか確認
        $response->assertSeeText('認証はこちらから');

        // ボタンのリンク先がMailhogのURLであるか確認
        $response->assertSee('http://localhost:8025');
    }

    /**
     * メール認証テスト③: メール認証リンクをクリックするとプロフィール設定画面に遷移する
     */
    public function test_email_verification_redirects_to_profile_edit()
    {
        // メール認証済みではないユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => null,
        ]);

        // 認証済みのメール検証リクエストを作成
        $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // メール認証リンクにアクセス
        $response = $this->actingAs($user)->get($verificationUrl);

        // プロフィール設定画面にリダイレクトされているか確認
        $response->assertRedirect('/profile_edit');

        // メール認証が完了したか確認（email_verified_atが設定されている）
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
