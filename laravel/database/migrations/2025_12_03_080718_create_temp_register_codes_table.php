<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temp_register_codes', function (Blueprint $table) {
            $table->id();
            $table->string('discord_id')->comment('DiscordのID');
            $table->string('register_code', 255)->comment('ハッシュ化をするため255文字にしておく');
            $table->date('expires_at')->comment('有効期限（日付のみ）');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temp_register_codes');
    }
};
