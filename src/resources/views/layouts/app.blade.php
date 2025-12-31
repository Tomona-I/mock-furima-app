<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <title>Document</title>
    @yield('css')
</head>

<body class="body">
    <header class="header-area">
        <a href="/index" class="header-logo">
            <img src="{{ asset('img/COACHTECHヘッダーロゴ.png') }}" alt="coachtechロゴ">
        </a>
        <form action="/index" method="get" class="header-search-form">
            <input type="text" name="keyword" class="header-search-input" placeholder="なにをお探しですか？" >
        </form>
        <form action="/logout" method="post" class="logout-button">
            @csrf
            <button type="submit" class="logout-button-link">ログアウト</button>
        </form>
        <div class="mypage-button">
            <a href="/mypage" class="mypage-button-link">マイページ</a>
        </div>
        <div class="sell-button">
            <a href="/sell" class="sell-button-link">出品</a>
        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>
    
</body>
</html>