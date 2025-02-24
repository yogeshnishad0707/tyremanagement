<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'role_id' => '1',
                // 'parent_id' => '',
                'name' => 'super admin',
                'email' => 'super@gmail.com',
                'mobile_no' => 'web',
                'address' => 'Raipur',
                'password' => '12345',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
        ]);
    }
}
