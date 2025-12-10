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
            <a href="{{ route('register1') }}" class="header-register-link">アカウント登録</a>
        </div>     
    </header>

    <div class="login">
        <p class="login-title">ログイン</p>
        <form action="" method="POST">
            @csrf
            <div class="input-info">
                <div class="userid">
                    <input type="text" placeholder="name" name="name" class="userid-input">
                </div>
                <div class="userid">
                    <input type="text" placeholder="password" name="password" id="password" class="password-input">
                </div>
            </div>
            <button type="submit" class="login-button">ログイン</button>
        </form>
    </div>
</body>
</html>