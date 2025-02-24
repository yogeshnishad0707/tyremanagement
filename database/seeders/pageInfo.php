<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class pageInfo extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pageinfos')->insert([
            [
                'pagename'=>'Add User',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
            [
                'pagename'=>'Manage Tyre Type',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
            [
                'pagename'=>'Manage Tyre Size',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
            [
                'pagename'=>'Manage Truck Make',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
        ]);
    }
}
