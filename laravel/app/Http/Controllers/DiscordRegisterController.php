<?php

namespace App\Http\Controllers;

use App\Models\TempRegisterCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class DiscordRegisterController extends Controller
{
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
            \Log::info('User IS in guild', ['discord_id' => $discordUserId]);

            return true;
        }

        if ($response->status() === 404) {
            \Log::warning('User NOT in guild (404)', ['discord_id' => $discordUserId]);

            return false;
        }

        \Log::error('Guild check error', [
            'discord_id' => $discordUserId,
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return false;
    }

    public function showRegisterForm()
    {
        return view('discord.register');
    }

    public function sendDiscordRegisterCode(Request $req)
    {
        $discordId = $req->input('discord_id');

        // ① ギルド所属チェック
        if (! $this->isUserInGuild($discordId)) {

            // ❗デバッグログ
            \Log::warning('User failed guild check', ['discord_id' => $discordId]);

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

        return redirect()->route('discorzd.register.confirm.form');
    }

    public function showConfirmForm()
    {
        return view('discord.confirm');
    }

    public function confirmRegisterCode(Request $req)
    {
        $req->validate([
            'code' => 'required|string|size:'.self::CODE_LENGTH,
        ]);

        $discordId = session('discord_id');
        $input = $req->input('code');

        $tmp = TempRegisterCode::where('discord_id', $discordId)
            ->where('expires_at', '>=', Carbon::today())
            ->get();

        foreach ($tmp as $row) {
            if (Hash::check($input, $row->register_code)) {
                return redirect('/')->with('status', '登録完了');
            }
        }

        return back()->withErrors(['code' => 'コードが正しくありません']);
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

        if (! $channelId) {
            \Log::error('Failed to create DM channel.', [
                'discord_id' => $discordUserId,
                'response' => $response->body(),
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
}
