@extends('layout')

@section('content')
<div class="container">
    <h2>新規登録</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">ユーザー名：</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <div>
            <label for="password">パスワード：</label>
            <input type="password" name="password" required>
        </div>

        <div>
            <label for="password_confirmation">パスワード確認：</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">登録</button>
    </form>
</div>
@endsection
