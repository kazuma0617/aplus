<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <title>Aplus</title>
</head>
<body>
    <header>
        <div class="header-inner">
            <a href="#" class="logo">Aplus</a>
            <a href="{{ route('login') }}" class="header-register-link">ログイン</a>
        </div>     
    </header>

    <div class="login">

        @if ($errors->any())
        <div class="error-box" style="color: red; margin-bottom: 10px;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <p class="login-title">新規登録</p>

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf

            <!-- Discord ID（hidden） -->
            <input type="hidden" name="discord_id" value="{{ session('discord_id') }}" />

            <div class="input-info">
                <div class="userid">
                    <input type="text" name=register_code class="userid-input" placeholder="確認コードを入力">
                </div>
                <div class="userid">
                    <input type="text" name="name" class="userid-input" placeholder="name">
                </div>
                <div class="userid">
                    <input type="password" name="password" class="userid-input" placeholder="password">
                </div>
            </div>

            <button type="submit" class="login-button">新規登録</button>
        </form>
    </div>
</body>
</html>
