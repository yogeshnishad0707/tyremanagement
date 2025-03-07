<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tyreStatusTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mtyrestatustypes')->insert([
            [
                'category_name'=>'running',
                'status'=>'1',
                'operatorid'=>'1',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
            [
                'category_name'=>'scrap',
                'status'=>'1',
                'operatorid'=>'1',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
            [
                'category_name'=>'replaced',
                'status'=>'1',
                'operatorid'=>'1',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
        ]);
    }
}
