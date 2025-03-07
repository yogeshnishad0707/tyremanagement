<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class positionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mtyrestatustypes')->insert([
            [
                'category_name'=>'FR',
                'type'=>'front',
                'status'=>'1',
                'operatorid'=>'1',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
            [
                'category_name'=>'FL',
                'type'=>'front',
                'status'=>'1',
                'operatorid'=>'1',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
            [
                'category_name'=>'RLI',
                'type'=>'rear',
                'status'=>'1',
                'operatorid'=>'1',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
            [
                'category_name'=>'RLO',
                'type'=>'rear',
                'status'=>'1',
                'operatorid'=>'1',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
            [
                'category_name'=>'RRO',
                'type'=>'rear',
                'status'=>'1',
                'operatorid'=>'1',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
            [
                'category_name'=>'RRO',
                'type'=>'rear',
                'status'=>'1',
                'operatorid'=>'1',
                'created_at' => now(),
                'updated_at'=>now(),
            ],
        ]);
    }
}
