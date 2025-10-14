<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tags')->insert([
            ['name' => 'Laravel'],
            ['name' => 'PHP'],
            ['name' => 'Vue'],
            ['name' => 'JavaScript'],
        ]);
    }
}
