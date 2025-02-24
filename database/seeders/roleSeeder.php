<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'super admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'site admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'operator',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'user',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at'=>now(),
            ]
        ]);
    }
}
