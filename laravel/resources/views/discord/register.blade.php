@extends('layout')

@section('content')
<form action="{{ route('discord.register.send') }}" method="POST">
    @csrf
    <label>Discord ID</label>
    <input type="text" name="discord_id" required>
    <button type="submit">コードを送信</button>
</form>
@endsection