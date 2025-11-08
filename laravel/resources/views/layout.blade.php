<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Aplus</title>
</head>
<body>
    <header>
        <div class="header-inner">
            <a href="#" class="logo">Aplus</a>
            <nav>
                <button class="icon-btn"><i class="bi bi-search"></i></button>
                <button class="icon-btn"><i class="bi bi-bell"></i></button>
                <a href="{{ route('mypage') }}" class="icon-btn"><i class="bi bi-person-circle"></i></a>
                <a href="{{ route('articles.create') }}">投稿する</a>
            </nav>
        </div>
        
    </header>

    <main class="container">
        @yield('content')
    </main>
</body>
</html>