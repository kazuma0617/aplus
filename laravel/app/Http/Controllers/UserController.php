<?php

namespace App\Http\Controllers;

use App\Models\TempRegisterCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
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

        return $response->successful();
    }

    public function sendDiscordRegisterCode(Request $request)
    {
        $discordId = $request->input('discord_id');

        if (! $this->isUserInGuild($discordId)) {
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
        $request->validate([
            'discord_id' => 'required|string',
            'register_code' => 'required|string',
            'name' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $discordId = $request->input('discord_id');
        $inputCode = $request->input('register_code');

        $record = TempRegisterCode::where('discord_id', $discordId)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $record) {
            return back()->withErrors([
                'register_code' => '認証コードが存在しないか、有効期限が切れています。',
            ])->withInput();
        }

        if (! Hash::check($inputCode, $record->register_code)) {
            return back()->withErrors([
                'register_code' => '認証コードが間違っています。',
            ])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'discord_id' => $discordId,
        ]);

        $record->delete();

        Auth::login($user);

        return redirect()->route('mypage');
    }

    protected function sendDiscordDM(string $discordUserId, string $message)
    {
        $botToken = config('services.discord.bot_token');

        $response = Http::withHeaders([
            'Authorization' => "Bot {$botToken}",
            'Content-Type' => 'application/json',
        ])->post('https://discord.com/api/v10/users/@me/channels', [
            'recipient_id' => $discordUserId,
        ]);

        $channelId = $response->json('id');

        if (! $channelId) {
            return false;
        }

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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
