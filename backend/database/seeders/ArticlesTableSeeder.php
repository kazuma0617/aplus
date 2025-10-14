<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticlesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('articles')->insert([
            [
                'title' => 'Laravel入門',
                'article_url' => 'https://zenn.dev/example/articles/laravel-intro',
                'github_url' => 'https://github.com/example/laravel-intro',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'PHPで作る簡単ブログ',
                'article_url' => 'https://qiita.com/example/items/php-blog',
                'github_url' => 'https://github.com/example/php-blog',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
