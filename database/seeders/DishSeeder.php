<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('dishes')->insert([
            'name' => 'Shan Noodle',
            'category_id' => 1,
            'description' => 'noodle desc',
            'price' => 2000
        ]);

        DB::table('dishes')->insert([
            'name' => 'Tofu Noodle',
            'category_id' => 1,
            'description' => 'noodle desc',
            'price' => 2500
        ]);

        DB::table('dishes')->insert([
            'name' => 'Chinese Fry Noodle',
            'category_id' => 1,
            'description' => 'noodle desc',
            'price' => 3000
        ]);
    }
}
