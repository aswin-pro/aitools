<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plans')->insert([
            "name"  => "Silver",
            "description"  => "Unlock Advanced AI Content Creation Tools with our Silver Plan.",
            "price"  => 24,
            "validity"  => 31,
            "template_counts" => 10,
            "templates" => '{"blog_outline":0,"blog_headline":1,"blog_description":1,"blog_story_ideas":1,"article_content":1,"paragraph":1,"summarization":1,"product_name":1,"product_description":1,"startup_name":1,"service_review":1,"youtube_video_titles":0,"youtube_video_tags":0,"youtube_video_outline":0,"youtube_video_intro":0,"youtube_video_ideas":0,"youtube_short_script":0,"write_for_me":0,"website_meta_description":0,"website_meta_keywords":0,"website_meta_title":0,"event_promotion_email":0,"twitter_writer":0,"presentation_content":0,"ask_question":0,"landing_page":0,"google_ads":0,"aida":0,"product_review":0,"welcome_email":0,"youtube_video_description":0,"custom_prompt":0,"generate_by_website_url":0}',
            "max_words" => 500,
            "max_images" => 100,
            "ai_speech_to_text" => 0,
            "ai_text_to_speech" => 0,
            "ai_code" => 0,
            "ai_chatgenius" => 0,
            "ai_docsassist" => 0,
            "ai_webchat" => 0,
            "additional_tools" => 1,
            "recommended"  => 1,
            "support" => 1
        ]);

        DB::table('plans')->insert([
            "name"  => "Gold",
            "description"  => "Get the Ultimate AI Content Creation Experience with our Gold Plan.",
            "price"  => 48,
            "validity"  => 31,
            "template_counts" => 21,
            "templates" => '{"blog_outline":1,"blog_headline":1,"blog_description":1,"blog_story_ideas":1,"article_content":1,"paragraph":1,"summarization":1,"product_name":1,"product_description":1,"startup_name":1,"service_review":1,"youtube_video_titles":1,"youtube_video_tags":1,"youtube_video_outline":1,"youtube_video_intro":1,"youtube_video_ideas":1,"youtube_short_script":1,"write_for_me":1,"website_meta_description":1,"website_meta_keywords":1,"website_meta_title":1,"event_promotion_email":0,"twitter_writer":0,"presentation_content":0,"ask_question":0,"landing_page":0,"google_ads":0,"aida":0,"product_review":0,"welcome_email":0,"youtube_video_description":0,"custom_prompt":1,"generate_by_website_url":0}',
            "max_words" => 1000,
            "max_images" => 500,
            "ai_speech_to_text" => 1,
            "ai_text_to_speech" => 0,
            "ai_code" => 0,
            "ai_chatgenius" => 1,
            "ai_docsassist" => 1,
            "ai_webchat" => 0,
            "additional_tools" => 1,
            "recommended"  => 0,
            "support" => 1
        ]);

        DB::table('plans')->insert([
            "name"  => "Platinum",
            "description"  => "Access Exclusive AI Content Creation Tools and Features with our Platinum Plan.",
            "price"  => 99,
            "validity"  => 31,
            "template_counts" => 33,
            "templates" => '{"blog_outline":1,"blog_headline":1,"blog_description":1,"blog_story_ideas":1,"article_content":1,"paragraph":1,"summarization":1,"product_name":1,"product_description":1,"startup_name":1,"service_review":1,"youtube_video_titles":1,"youtube_video_tags":1,"youtube_video_outline":1,"youtube_video_intro":1,"youtube_video_ideas":1,"youtube_short_script":1,"write_for_me":1,"website_meta_description":1,"website_meta_keywords":1,"website_meta_title":1,"event_promotion_email":1,"twitter_writer":1,"presentation_content":1,"ask_question":1,"landing_page":1,"google_ads":1,"aida":1,"product_review":1,"welcome_email":1,"youtube_video_description":1,"custom_prompt":1,"generate_by_website_url":1}',
            "max_words" => 10000,
            "max_images" => 1000,
            "ai_speech_to_text" => 1,
            "ai_text_to_speech" => 1,
            "ai_code" => 1,
            "ai_chatgenius" => 1,
            "ai_docsassist" => 1,
            "ai_webchat" => 1,
            "additional_tools" => 1,
            "recommended"  => 0,
            "support" => 1
        ]);
    }
}
