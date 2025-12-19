<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('style')
    <title>Aplus</title>
</head>
<body>
    <header>
        <div class="header-inner">
            <a href="#" class="logo">Aplus</a>
            <nav>
                <a href="{{ route('mypage') }}" class="icon-btn"><i class="bi bi-person-circle"></i></a>
                <a href="{{ route('articles.create') }}">投稿する</a>
            </nav>
        </div>
        
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>