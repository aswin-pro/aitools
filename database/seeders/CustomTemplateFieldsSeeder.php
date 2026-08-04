<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomTemplateFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 11
        DB::table('custom_template_fields')->insert([
            "template_id" => 11,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Website Name",
            "field_description"  => "Eg: CAPSI"
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 11,
            "ai_input" => "##input2##",
            "field_type"  => "textarea",
            "field_name"  => "Keywords of your website",
            "field_description"  => "Eg: service, application, brands"
        ]);

        // 10
        DB::table('custom_template_fields')->insert([
            "template_id" => 10,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Topic",
            "field_description"  => "Eg: Write your presentation topic"
        ]);

        // 9
        DB::table('custom_template_fields')->insert([
            "template_id" => 9,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Question",
            "field_description"  => "Eg: What is AI?"
        ]);

        // 8
        DB::table('custom_template_fields')->insert([
            "template_id" => 8,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "What would you like to expand on?",
            "field_description"  => "Eg: On the first day of her trip, Diane visited the Golden Gate"
        ]);

        // 7
        DB::table('custom_template_fields')->insert([
            "template_id" => 7,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "What would you like to summarize?",
            "field_description"  => "Eg: Your content"
        ]);

        // 6
        DB::table('custom_template_fields')->insert([
            "template_id" => 6,
            "ai_input" => "##input1##",
            "field_type"  => "text",
            "field_name"  => "Description of Paragraph",
            "field_description"  => "Eg: Best tourist destination india or UAE?"
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 6,
            "ai_input" => "##input2##",
            "field_type"  => "textarea",
            "field_name"  => "Keywords you use in the paragraph",
            "field_description"  => "Eg: india, uae, tourist destination"
        ]);

        // 5
        DB::table('custom_template_fields')->insert([
            "template_id" => 5,
            "ai_input" => "##input1##",
            "field_type"  => "text",
            "field_name"  => "What topic would you like to write about?",
            "field_description"  => "Eg: Horror Story"
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 5,
            "ai_input" => "##input2##",
            "field_type"  => "text",
            "field_name"  => "What keywords are you using for this article?",
            "field_description"  => "Eg: Horror, story, novel"
        ]);

        // 4
        DB::table('custom_template_fields')->insert([
            "template_id" => 4,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "What type of blog post do you want to create?",
            "field_description"  => "Eg: Latest technology"
        ]);

        // 3
        DB::table('custom_template_fields')->insert([
            "template_id" => 3,
            "ai_input" => "##input1##",
            "field_type"  => "text",
            "field_name"  => "Title of your blog post",
            "field_description"  => "Eg: Latest technology"
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 3,
            "ai_input" => "##input2##",
            "field_type"  => "textarea",
            "field_name"  => "Keywords of your blog post",
            "field_description"  => "Eg: latest technology, technology, new technology"
        ]);

        // 2
        DB::table('custom_template_fields')->insert([
            "template_id" => 2,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "About your blog post",
            "field_description"  => "Eg: Tell me about the latest technology"
        ]);

        // 1
        DB::table('custom_template_fields')->insert([
            "template_id" => 1,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Blog Topic",
            "field_description"  => "Eg: How to use AI for blog writing?"
        ]);

        // 12
        DB::table('custom_template_fields')->insert([
            "template_id" => 12,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Meta Name",
            "field_description"  => "Eg: CAPSI"
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 12,
            "ai_input" => "##input2##",
            "field_type"  => "textarea",
            "field_name"  => "Meta Description",
            "field_description"  => "Eg: Get the tools you need to stay organized and make decisions faster."
        ]);

        // 13
        DB::table('custom_template_fields')->insert([
            "template_id" => 13,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Website Name",
            "field_description"  => "Eg: CAPSI"
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 13,
            "ai_input" => "##input2##",
            "field_type"  => "textarea",
            "field_name"  => "Keywords of your website",
            "field_description"  => "Eg: service, application, brands"
        ]);

        // 14
        DB::table('custom_template_fields')->insert([
            "template_id" => 14,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Company/Brand Name",
            "field_description"  => "Eg: AI Tools"
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 14,
            "ai_input" => "##input2##",
            "field_type"  => "textarea",
            "field_name"  => "What Products/Services Do You Offer",
            "field_description"  => "Eg: We are a development platform"
        ]);

        // 15
        DB::table('custom_template_fields')->insert([
            "template_id" => 15,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Topic",
            "field_description"  => "Eg: Twitter CEO Elon Musk apologises for annoying ads."
        ]);

        // 16
        DB::table('custom_template_fields')->insert([
            "template_id" => 16,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Event Name",
            "field_description"  => "Eg: Google I/O 2023"
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 16,
            "ai_input" => "##input2##",
            "field_type"  => "textarea",
            "field_name"  => "Description of your event",
            "field_description"  => "Eg: Google I/O is an annual developer conference held by Google in Mountain View, California."
        ]);

        // 17
        DB::table('custom_template_fields')->insert([
            "template_id" => 17,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Service / Product Name",
            "field_description"  => "Eg: CAPSI"
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 17,
            "ai_input" => "##input2##",
            "field_type"  => "textarea",
            "field_name"  => "Description of your service / product",
            "field_description"  => "Eg: CAPSI Newsletter to keep you updated with new research"
        ]);

        // 18
        DB::table('custom_template_fields')->insert([
            "template_id" => 18,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Write about your video.",
            "field_description"  => "Eg: About tourist places in India"
        ]);

        // 19
        DB::table('custom_template_fields')->insert([
            "template_id" => 19,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Video Title",
            "field_description"  => "Eg: \'Discover India\'s Top Tourist Destinations - An Unforgettable Journey!\'"
        ]);

        // 20
        DB::table('custom_template_fields')->insert([
            "template_id" => 20,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Video Title",
            "field_description"  => "Eg: \'Discover India\'s Top Tourist Destinations - An Unforgettable Journey!\'"
        ]);

        // 21
        DB::table('custom_template_fields')->insert([
            "template_id" => 21,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Channel Name",
            "field_description"  => "Eg: Mr. India"
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 21,
            "ai_input" => "##input2##",
            "field_type"  => "textarea",
            "field_name"  => "Video Title",
            "field_description"  => "Eg: \'Discover India\'s Top Tourist Destinations - An Unforgettable Journey!\'"
        ]);

        // 22
        DB::table('custom_template_fields')->insert([
            "template_id" => 22,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Topic",
            "field_description"  => "Eg: Travel"
        ]);

        // 23
        DB::table('custom_template_fields')->insert([
            "template_id" => 23,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Video Title",
            "field_description"  => "Eg: How to create youtube shorts?"
        ]);

        // 24
        DB::table('custom_template_fields')->insert([
            "template_id" => 24,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "What is your video about?",
            "field_description"  => "Eg: How To Start YouTube Channel | English"
        ]);

        // 25
        DB::table('custom_template_fields')->insert([
            "template_id" => 25,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Provider Name",
            "field_description"  => "Eg: Ola Cabs"
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 25,
            "ai_input" => "##input2##",
            "field_type"  => "textarea",
            "field_name"  => "Service Name",
            "field_description"  => "Eg: Rental Car"
        ]);

        // 26
        DB::table('custom_template_fields')->insert([
            "template_id" => 26,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Store Name",
            "field_description"  => "Eg: Flipkart"
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 26,
            "ai_input" => "##input2##",
            "field_type"  => "textarea",
            "field_name"  => "Product Name",
            "field_description"  => "Eg: Dell WM118 Wireless Mouse"
        ]);

        // 27
        DB::table('custom_template_fields')->insert([
            "template_id" => 27,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Product Description Details",
            "field_description"  => "Eg: Great Aesthetics, Usage ideal for office, education sectors, designing, basic gaming etc"
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 27,
            "ai_input" => "##input2##",
            "field_type"  => "textarea",
            "field_name"  => "Keywords of your product",
            "field_description"  => "Eg: dell, wireless, mouse"
        ]);

        // 28
        DB::table('custom_template_fields')->insert([
            "template_id" => 28,
            "ai_input" => "##input1##",
            "field_type"  => "text",
            "field_name"  => "Product Name Details",
            "field_description"  => "Eg: Dell wireless mouse"
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 28,
            "ai_input" => "##input2##",
            "field_type"  => "textarea",
            "field_name"  => "Eg: Dell wireless mouse",
            "field_description"  => "Eg: dell, wireless, mouse"
        ]);

        // 29
        DB::table('custom_template_fields')->insert([
            "template_id" => 29,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Business Model",
            "field_description"  => "Eg: Ecommerce or electronic commerce is the trading of goods and services on the internet."
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 29,
            "ai_input" => "##input2##",
            "field_type"  => "textarea",
            "field_name"  => "Keywords of your business",
            "field_description"  => "Eg: ecommerce, online, eshop"
        ]);

        // 30
        DB::table('custom_template_fields')->insert([
            "template_id" => 30,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Company/Brand Name",
            "field_description"  => "Eg: AI Tools"
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 30,
            "ai_input" => "##input2##",
            "field_type"  => "textarea",
            "field_name"  => "What Products/Services Do You Offer",
            "field_description"  => "Eg: We are a development platform"
        ]);

        // 31
        DB::table('custom_template_fields')->insert([
            "template_id" => 31,
            "ai_input" => "##input1##",
            "field_type"  => "textarea",
            "field_name"  => "Company/Brand Name",
            "field_description"  => "Eg: Writer"
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 31,
            "ai_input" => "##input2##",
            "field_type"  => "textarea",
            "field_name"  => "What Products/Services Do You Offer",
            "field_description"  => "Eg: Help generating ideas, organizing your thoughts, or crafting a compelling piece of writing."
        ]);

        // 32
        DB::table('custom_template_fields')->insert([
            "template_id" => 32,
            "ai_input" => "##input1##",
            "field_type"  => "url",
            "field_name"  => "Enter url of your website",
            "field_description"  => "Eg: http://domain.com"
        ]);

        DB::table('custom_template_fields')->insert([
            "template_id" => 32,
            "ai_input" => "##input2##",
            "field_type"  => "textarea",
            "field_name"  => "Description",
            "field_description"  => "Eg: Latest technology"
        ]);

        // 33
        DB::table('custom_template_fields')->insert([
            "template_id" => 33,
            "ai_input" => "##input1##",
            "field_type"  => "text",
            "field_name"  => "What topic would you like to write about?",
            "field_description"  => "Eg: Horror Story"
        ]);
    }
}
