<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleTagTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('article_tags')->insert([
            ['article_id' => 1, 'tag_id' => 1], // Laravel入門 → Laravel
            ['article_id' => 2, 'tag_id' => 2], // PHPブログ → PHP
        ]);
    }
}
