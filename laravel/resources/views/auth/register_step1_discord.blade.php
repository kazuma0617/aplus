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
        <form action="{{ route('register1') }}" method="POST">
            @csrf
            <div class="input-info">
                <fieldset class="userid">
                     <input class="userid-input" name="discord_id" type="text" placeholder="Discord IDを入力" required/>
                </fieldset>
            </div>
            <button type="submit" class="login-button">Discordに送信</button>
        </form>
    </div>
</body>
</html>