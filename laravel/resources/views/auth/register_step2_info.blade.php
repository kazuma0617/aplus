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
        <p class="login-title">新規登録</p>
       <form action="{{ route('register.submit') }}" method="POST">
    @csrf
    @if ($errors->any())
    <div style="color: red; margin-bottom: 10px;">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif


    <!-- Discord ID（hiddenでもOK） -->
    <input class="password-input hidden" 
           name="discord_id" 
           type="text"
           placeholder="Discord IDを入力"
           value="{{ old('discord_id') ?? session('discord_id') }}" />

    <!-- 認証コード -->
    <input class="password-input"
           name="register_code"
           type="text"
           placeholder="確認コードを入力"
           value="{{ old('register_code') }}" />

    <!-- username -->
    <fieldset class="userid">
        <input type="text"
               placeholder="username"
               name="name"
               class="userid-input">
    </fieldset>

    <!-- password -->
    <fieldset class="password">
        <input type="password"
               placeholder="password"
               name="password"
               id="password"
               class="password-input">
    </fieldset>

    <!-- password confirmation -->
    <fieldset class="password">
        <input type="password"
               placeholder="確認用パスワード"
               name="password_confirmation"
               class="password-input">
    </fieldset>

    <button type="submit" class="login-button">新規登録</button>
</form>

    </div>
</body>
</html>