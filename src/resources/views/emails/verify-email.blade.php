<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <p>こんにちは {{ $notifiable->name }} さん</p>
    
    <p>アカウント登録を完了するために、下のボタンをクリックしてメールアドレスを認証してください。</p>
    
    <p>
        <a href="{{ $actionUrl }}" style="display: inline-block; padding: 10px 20px; background-color: #ff5555; color: white; text-decoration: none; border-radius: 4px;">
            メールアドレスを認証する
        </a>
    </p>
    
    <p>このリンクの有効期限は24時間です。</p>
    
    <p>※このメールに心当たりがない場合は、無視してください。</p>
    
    <hr>
    
    <p>{{ config('app.name') }}</p>
</body>
</html>
