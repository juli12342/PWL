<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('books')->delete();
        for ($i = 1; $i <= 3; $i++) {
            for ($j = 1; $j <= 3; $j++) {
                DB::table('books')->insert([
                    'code' => "B".fake()->randomNumber(3, true),
                    'title' => "Biografi : ".fake()->name,
                    'id_publisher' => $i
                ]);
            }
        }
    }
}
