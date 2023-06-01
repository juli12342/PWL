<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'email' => 'julihartatigea@gmail.com',
            'password' => password_hash('admin', PASSWORD_DEFAULT),
            'name' => 'Superadmin',
            'role' => 'superadmin'
        ]);
    }
}
