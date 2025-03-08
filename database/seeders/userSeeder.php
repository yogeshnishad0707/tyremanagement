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
                'mobile_no' => '8103490175',
                'address' => 'Raipur',
                'password' => '$2y$12$wa4Vx7qye.6nSi2QqjZ0n.lrRD6nfS1t//KIsf3aMUaMruFvAU6Oy',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
        ]);
    }
}
