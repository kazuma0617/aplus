<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TempRegisterCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class UserController extends Controller
{
    // 登録画面
    public function showRegisterForm1()
    {
        return view('auth.register_step1_discord');
    }

    const CODE_LENGTH = 16;

    protected function isUserInGuild(string $discordUserId): bool
    {
        $botToken = config('services.discord.bot_token');
        $guildId = config('services.discord.guild_id');

        $response = Http::withHeaders([
            'Authorization' => "Bot {$botToken}",
        ])->get("https://discord.com/api/v10/guilds/{$guildId}/members/{$discordUserId}");

        // 🔍 デバッグログ
        \Log::info('Guild membership check', [
            'discord_id' => $discordUserId,
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        if ($response->successful()) {
            \Log::info("User IS in guild", ['discord_id' => $discordUserId]);
            return true;
        }

        if ($response->status() === 404) {
            \Log::warning("User NOT in guild (404)", ['discord_id' => $discordUserId]);
            return false;
        }

        \Log::error("Guild check error", [
            'discord_id' => $discordUserId,
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return false;
    }

    public function sendDiscordRegisterCode(Request $req)
    {
        $discordId = $req->input('discord_id');

        // ① ギルド所属チェック
        if (!$this->isUserInGuild($discordId)) {

            // ❗デバッグログ
            \Log::warning("User failed guild check", ['discord_id' => $discordId]);

            return back()->withErrors([
                'discord_id' => '指定のDiscordサーバーに参加していません。',
            ]);
        }

        // 乱数コード生成
        $code = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, self::CODE_LENGTH);
        $hashed = Hash::make($code);
        $expires = Carbon::tomorrow();

        TempRegisterCode::create([
            'discord_id' => $discordId,
            'register_code' => $hashed,
            'expires_at' => $expires,
        ]);

        // DM送信
        $this->sendDiscordDM($discordId, "あなたの登録コードは **{$code}** です。");

        session(['discord_id' => $discordId]);

        return redirect()->route('register2', ['discord_id' => $discordId]);
    }

    public function showRegisterForm2()
    {
        return view('auth.register_step2_info');
    }

public function newRegister(Request $request)
{
    // 入力バリデーション
    $request->validate([
        'discord_id' => 'required|string',
        'register_code' => 'required|string',
        'name' => 'required|string',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $discordId = $request->input('discord_id');
    $inputCode = $request->input('register_code');

    // ① TempRegisterCode を取得
    $record = TempRegisterCode::where('discord_id', $discordId)
                ->where('expires_at', '>', now())
                ->latest()
                ->first();

    if (!$record) {
        return back()->withErrors([
            'register_code' => '認証コードが存在しないか、有効期限が切れています。',
        ])->withInput();
    }

    // ② 認証コードをハッシュチェック
    if (!Hash::check($inputCode, $record->register_code)) {
        return back()->withErrors([
            'register_code' => '認証コードが間違っています。',
        ])->withInput();
    }

    // ③ ユーザー作成
    $user = User::create([
        'name' => $request->name,
        'password' => Hash::make($request->password),
        'discord_id' => $discordId,
    ]);

    // ④ 認証コードを削除（セキュリティ）
    $record->delete();

    // ⑤ ログイン
    Auth::login($user);

    // ⑥ 遷移先
    return redirect()->route('mypage')->with('success', 'ユーザー登録が完了しました！');
}

protected function sendDiscordDM(string $discordUserId, string $message)
    {
        $botToken = config('services.discord.bot_token');

        // DM チャンネル作成
        $response = Http::withHeaders([
            'Authorization' => "Bot {$botToken}",
            'Content-Type' => 'application/json',
        ])->post('https://discord.com/api/v10/users/@me/channels', [
            'recipient_id' => $discordUserId,
        ]);

        $channelId = $response->json('id');

        if (!$channelId) {
            \Log::error('Failed to create DM channel.', [
                'discord_id' => $discordUserId,
                'response' => $response->body()
            ]);
            return false;
        }

        // DM送信
        return Http::withHeaders([
            'Authorization' => "Bot {$botToken}",
            'Content-Type' => 'application/json',
        ])->post("https://discord.com/api/v10/channels/{$channelId}/messages", [
            'content' => $message,
        ]);
    }


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
