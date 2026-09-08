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
        // content templates
        $content_templates = json_encode([
            "aida"                      => 1,
            "paragraph"                 => 1,
            "google_ads"                => 1,
            "ask_question"              => 1,
            "blog_outline"              => 1,
            "landing_page"              => 1,
            "product_name"              => 1,
            "startup_name"              => 1,
            "write_for_me"              => 1,
            "blog_headline"             => 1,
            "custom_prompt"             => 1,
            "summarization"             => 1,
            "welcome_email"             => 1,
            "product_review"            => 1,
            "service_review"            => 1,
            "twitter_writer"            => 1,
            "article_content"           => 1,
            "blog_description"          => 1,
            "blog_story_ideas"          => 1,
            "website_meta_title"        => 1,
            "youtube_video_tags"        => 1,
            "product_description"       => 1,
            "youtube_video_ideas"       => 1,
            "youtube_video_intro"       => 1,
            "presentation_content"      => 1,
            "youtube_short_script"      => 1,
            "youtube_video_titles"      => 1,
            "event_promotion_email"     => 1,
            "website_meta_keywords"     => 1,
            "youtube_video_outline"     => 1,
            "generated_by_website_url"   => 1,
            "website_meta_description"  => 1,
            "youtube_video_description" => 1,
        ]);

        DB::table('plans')->insert([
            "name"              => "Silver",
            "description"       => "Unlock Advanced AI Content Creation Tools with our Silver Plan.",
            "price"             => 24,
            "validity"          => 31,
            "content_templates" => $content_templates,
            "ai_credits"        => 500,
            "ai_image_credits"  => 100,
            "speech_to_text"    => 0,
            "text_to_speech"    => 0,
            "code_generator"    => 0,
            "personalized_chat" => 0,
            "document_analyzer" => 0,
            "site_analyzer"     => 0,
            "is_recommended"    => 1,
            "customer_support"  => 1,
        ]);

        DB::table('plans')->insert([
            "name"              => "Gold",
            "description"       => "Get the Ultimate AI Content Creation Experience with our Gold Plan.",
            "price"             => 48,
            "validity"          => 31,
            "content_templates" => $content_templates,
            "ai_credits"        => 1000,
            "ai_image_credits"  => 500,
            "speech_to_text"    => 1,
            "text_to_speech"    => 0,
            "code_generator"    => 0,
            "personalized_chat" => 1,
            "document_analyzer" => 1,
            "site_analyzer"     => 0,
            "is_recommended"    => 0,
            "customer_support"  => 1,
        ]);

        DB::table('plans')->insert([
            "name"              => "Platinum",
            "description"       => "Access Exclusive AI Content Creation Tools and Features with our Platinum Plan.",
            "price"             => 99,
            "validity"          => 31,
            "content_templates" => $content_templates,
            "ai_credits"        => 10000,
            "ai_image_credits"  => 1000,
            "speech_to_text"    => 1,
            "text_to_speech"    => 1,
            "code_generator"    => 1,
            "personalized_chat" => 1,
            "document_analyzer" => 1,
            "site_analyzer"     => 1,
            "is_recommended"    => 0,
            "customer_support"  => 1,
        ]);
    }
}
