<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 登録画面
    public function showRegisterForm1()
    {
        return view('auth.register_step1_discord');
    }

    public function sendDiscordRegisterCode(Request $request)
{
    // Discord ID を取得
    $discordId = $request->input('discord_id');

    // セッション保存
    session(['discord_id' => $discordId]);

    // 認証コード生成（16文字）
    $registerCode = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 16);

    // 有効期限（明日）
    $expiresAt = Carbon::tomorrow()->toDateString();

    // DB 保存（TempRegisterCodes）
    TempRegisterCode::create([
        'discord_id'   => $discordId,
        'register_code'=> bcrypt($registerCode),
        'expires_at'   => $expiresAt,
    ]);

    // Discord DM 送信（Controller内に直接書く）
    $botToken = env('DISCORD_BOT_TOKEN');

    // ① DMチャネル作成
    $response = Http::withHeaders([
        'Authorization' => "Bot {$botToken}",
        'Content-Type'  => 'application/json',
    ])->post('https://discord.com/api/v10/users/@me/channels', [
        'recipient_id' => $discordId,
    ]);

    $channelId = $response->json('id');

    if (!$channelId) {
        return back()->withErrors(['discord_id' => 'DMチャネルの作成に失敗しました']);
    }

    // ② メッセージ送信
    Http::withHeaders([
        'Authorization' => "Bot {$botToken}",
        'Content-Type'  => 'application/json',
    ])->post("https://discord.com/api/v10/channels/{$channelId}/messages", [
        'content' => "あなたの登録コードは **{$registerCode}** です。\n有効期限: 明日まで",
    ]);

    // 次の画面へ
    return redirect()->route('register2');
}


    // ログイン画面
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ログイン処理
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('mypage');
        }

        return back()->withErrors([
            'name' => 'ユーザー名またはパスワードが間違っています。',
        ]);
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
