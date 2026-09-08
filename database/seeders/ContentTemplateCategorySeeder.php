<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentTemplateCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('content_template_categories')->insert([
            "category_name"  => "Blog"
        ]);

        DB::table('content_template_categories')->insert([
            "category_name"  => "Content"
        ]);

        DB::table('content_template_categories')->insert([
            "category_name"  => "Website"
        ]);

        DB::table('content_template_categories')->insert([
            "category_name"  => "Social Media"
        ]);

        DB::table('content_template_categories')->insert([
            "category_name"  => "Email"
        ]);

        DB::table('content_template_categories')->insert([
            "category_name"  => "Video"
        ]);

        DB::table('content_template_categories')->insert([
            "category_name"  => "Review"
        ]);

        DB::table('content_template_categories')->insert([
            "category_name"  => "Product and Startup"
        ]);

        DB::table('content_template_categories')->insert([
            "category_name"  => "Others"
        ]);
    }
}
