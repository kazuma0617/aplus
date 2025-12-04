<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempRegisterCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class DiscordRegisterController extends Controller
{
    const CODE_LENGTH = 16;

    public function showRegisterForm()
    {
        return view('discord.register');
    }

    public function sendDiscordRegisterCode(Request $req)
    {
        $discordId = $req->input('discord_id');

        // 乱数コード生成
        $code = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, self::CODE_LENGTH);
        $hashed = Hash::make($code);
        $expires = Carbon::tomorrow();

        // DB保存
        TempRegisterCode::create([
            'discord_id' => $discordId,
            'register_code' => $hashed,
            'expires_at' => $expires,
        ]);

        // Discord Bot 経由で DM 送信
        $this->sendDiscordDM($discordId, "あなたの登録コードは **{$code}** です。");

        session(['discord_id' => $discordId]);

        return redirect()->route('discord.register.confirm.form');
    }

    public function showConfirmForm()
    {
        return view('discord.confirm');
    }

    public function confirmRegisterCode(Request $req)
    {
        $req->validate([
            'code' => "required|string|size:" . self::CODE_LENGTH,
        ]);

        $discordId = session('discord_id');
        $input = $req->input('code');

        $tmp = TempRegisterCode::where('discord_id', $discordId)
                ->where('expires_at', '>=', Carbon::today())
                ->get();

        foreach ($tmp as $row) {
            if (Hash::check($input, $row->register_code)) {
                // 本登録処理
                // 例: User::create([...discord_id など...]);
                return redirect('/')->with('status', '登録完了');
            }
        }

        return back()->withErrors(['code' => 'コードが正しくありません']);
    }

   protected function sendDiscordDM(string $discordUserId, string $message)
{
    $botToken = config('services.discord.bot_token');

    // ① DM チャンネル作成
    $response = Http::withHeaders([
        'Authorization' => "Bot {$botToken}",
        'Content-Type' => 'application/json',
    ])->post('https://discord.com/api/v10/users/@me/channels', [
        'recipient_id' => $discordUserId,
    ]);

    $channelId = $response->json('id');

    if (!$channelId) {
        \Log::error('Failed to create DM channel.', ['response' => $response->json()]);
        return false;
    }

    // ② DM チャンネルにメッセージ送信
    return Http::withHeaders([
        'Authorization' => "Bot {$botToken}",
        'Content-Type' => 'application/json',
    ])->post("https://discord.com/api/v10/channels/{$channelId}/messages", [
        'content' => $message,
    ]);
}

}
