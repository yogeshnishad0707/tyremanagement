<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class permissionCategory extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissioncategories')->insert([
            [
                'pc_name'=>'View',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
            [
                'pc_name'=>'Add',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
            [
                'pc_name'=>'Update',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
            [
                'pc_name'=>'Delete',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
        ]);
    }
}
