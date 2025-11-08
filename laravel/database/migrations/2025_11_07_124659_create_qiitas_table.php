<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qiitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('qiita_token');
            $table->timestamps();
        });

        // usersテーブルからqiitatokenを削除
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('qiita_token');
        });
    }

    public function down(): void
    {
        // 元に戻す
        Schema::table('users', function (Blueprint $table) {
            $table->string('qiita_token')->nullable();
        });

        Schema::dropIfExists('qiitas');
    }
};
