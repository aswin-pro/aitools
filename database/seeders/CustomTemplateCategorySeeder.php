<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomTemplateCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('custom_template_categories')->insert([
            "category_name"  => "Blog"
        ]);

        DB::table('custom_template_categories')->insert([
            "category_name"  => "Content"
        ]);

        DB::table('custom_template_categories')->insert([
            "category_name"  => "Website"
        ]);

        DB::table('custom_template_categories')->insert([
            "category_name"  => "Social Media"
        ]);

        DB::table('custom_template_categories')->insert([
            "category_name"  => "Email"
        ]);

        DB::table('custom_template_categories')->insert([
            "category_name"  => "Video"
        ]);

        DB::table('custom_template_categories')->insert([
            "category_name"  => "Review"
        ]);

        DB::table('custom_template_categories')->insert([
            "category_name"  => "Product and Startup"
        ]);

        DB::table('custom_template_categories')->insert([
            "category_name"  => "Others"
        ]);
    }
}
