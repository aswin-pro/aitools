<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('themes')->insert([
            "theme_id" => "513402991882314",
            "cover_image" => "images/themes/classic.png",
            "theme_name" => "Classic"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "330599619570398",
            "cover_image" => "images/themes/modern-green.png",
            "theme_name" => "Modern Green"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "317109101703740",
            "cover_image" => "images/themes/modern-orange.png",
            "theme_name" => "Modern Orange"
        ]);
    }
}
