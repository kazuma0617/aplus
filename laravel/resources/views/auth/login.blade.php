@extends('layout')

@section('content')
<div class="container" style="max-width: 400px; margin-top: 50px;">

    <h2>ログイン</h2>

    {{-- エラーメッセージ --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">ユーザー名</label>
            <input type="text" name="name" id="name" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">パスワード</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-2">ログイン</button>
    </form>

    <p class="mt-3">
        アカウントをお持ちでない方は
        <a href="{{ route('register.show') }}">新規登録</a>
    </p>

</div>
@endsection
