@extends('layout')

@section('content')
<form action="{{ route('discord.register.confirm') }}" method="POST">
    @csrf
    <label>Discord に送られたコード</label>
    <input type="text" name="code" required>
    <button type="submit">登録</button>
</form>
@endsection