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
    </header>

    <main class="main">
        @yield('content')
    </main>
    
</body>
</html>