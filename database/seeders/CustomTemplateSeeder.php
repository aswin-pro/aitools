<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1
        DB::table('custom_templates')->insert([
            "category_id" => 1,
            "unique_slug"  => "blog_outline",
            "name"  => "Blog Outline",
            "description"  => "Generate blog outline from a given topic",
            "prompt"  => "Generate ##results## ##tone## blog outline in ##lang## from a given topic \"##input1##\""
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 1,
            "unique_slug"  => "blog_headline",
            "name"  => "Blog Headline",
            "description"  => "Maintaining a blog is a proven way to drive traffic to your website through SEO.",
            "prompt"  => "Write ##tone## ##results## blog headings in ##lang## with this blog description ##input1##"
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 1,
            "unique_slug"  => "blog_description",
            "name"  => "Blog Description",
            "description"  => "Generate description ideas for your articles and blog posts.",
            "prompt"  => "Write ##results ## ##tone## blog posts in ##lang## with keywords \"##input2##\" on \"##input1##\""
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 1,
            "unique_slug"  => "blog_story_ideas",
            "name"  => "Blog Story Ideas",
            "description"  => "A great tool to create blog stories that people love the most.",
            "prompt"  => "Write \"##input1##\" ##results## ##tone## blog post in ##lang##"
        ]);

        // 2
        DB::table('custom_templates')->insert([
            "category_id" => 2,
            "unique_slug"  => "article_content",
            "name"  => "Article Content",
            "description"  => "Create appealing and awe-inspiring content for the viewers.",
            "prompt"  => "Write ##results## ##tone## articles in ##lang## with the title \"##input1##\" using keywords ##input2##"
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 2,
            "unique_slug"  => "paragraph",
            "name"  => "Paragraph",
            "description"  => "You can use any one-word keywords to create an engaging paragraph.",
            "prompt"  => "Write ##results## ##tone## paragraphs in ##lang## on the topic ##input1## and using keywords ##input2##"
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 2,
            "unique_slug"  => "summarization",
            "name"  => "Summarization",
            "description"  => "Tool that represents the most important information from original content.",
            "prompt"  => "##tone## \"##input1##\" Write ##results## ##lang## summarization using only important words in these lines."
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 2,
            "unique_slug"  => "write_for_me",
            "name"  => "Write for me",
            "description"  => "Continue and complete my unfinished paragraph.",
            "prompt"  => "##tone## ##input1## write and complete my unfinished sentence in ##lang##"
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 2,
            "unique_slug"  => "ask_question",
            "name"  => "Ask Question",
            "description"  => "Get answer to all your questions",
            "prompt"  => "Generate ##results## ##tone## best anwsers in ##lang## based on question ##input1##"
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 2,
            "unique_slug"  => "presentation_content",
            "name"  => "Presentation Content",
            "description"  => "Write an engaging presentation content",
            "prompt"  => "Write ##results## ##tone## AI presentation (ppt) content in ##lang## based on topic ##input1##"
        ]);

        // 3
        DB::table('custom_templates')->insert([
            "category_id" => 3,
            "unique_slug"  => "website_meta_description",
            "name"  => "Website Meta Description",
            "description"  => "Write SEO friendly meta description for your website.",
            "prompt"  => "Generate ##results## ##tone## website meta description in ##lang## on the website ##input1## and using these keywords ##input2##"
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 3,
            "unique_slug"  => "website_meta_keywords",
            "name"  => "Website Meta Keywords",
            "description"  => "Write SEO-friendly meta keywords for your website.",
            "prompt"  => "Generate ##results## ##tone## website meta keywords in ##lang## on the website meta name \"##input1##\" and meta description ##input2##"
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 3,
            "unique_slug"  => "website_meta_title",
            "name"  => "Website Meta Title",
            "description"  => "Write SEO friendly meta title for your website.",
            "prompt"  => "Generate ##results## ##tone## website meta title in ##lang## on the website name \"##input1##\" and using these keywords \"##input2##\""
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 3,
            "unique_slug"  => "landing_page",
            "name"  => "Landing Page",
            "description"  => "Write hero text & description",
            "prompt"  => "Write ##results## ##tone## website landing page heading and description in ##lang## based on \"##input1##\" and using these keywords \"##input2##\""
        ]);

        // 4
        DB::table('custom_templates')->insert([
            "category_id" => 4,
            "unique_slug"  => "twitter_writer",
            "name"  => "Twitter Writer",
            "description"  => "Write a tweet on the latest news",
            "prompt"  => "Write ##results## ##tone## twitter content in ##lang## on the topic \"##input1##\""
        ]);

        // 5
        DB::table('custom_templates')->insert([
            "category_id" => 5,
            "unique_slug"  => "event_promotion_email",
            "name"  => "Event Promotion Email",
            "description"  => "Write email to promote your event",
            "prompt"  => "Write ##results## ##tone## event promotion email in ##lang## on the event name \"##input1##\" and description ##input2##"
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 5,
            "unique_slug"  => "welcome_email",
            "name"  => "Welcome Email",
            "description"  => "Write personalized email to welcome the customer for oining your service / product.",
            "prompt"  => "Write ##results## ##tone## welcome email in ##lang## on the service / product name \"##input1##\" and description ##input2##"
        ]);

        // 6
        DB::table('custom_templates')->insert([
            "category_id" => 6,
            "unique_slug"  => "youtube_video_titles",
            "name"  => "YouTube Video Titles",
            "description"  => "Write YouTube video titles that will attract viewers.",
            "prompt"  => "Write ##results## ##tone## youtube titles in ##lang## on this video description \"##input1##\""
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 6,
            "unique_slug"  => "youtube_video_tags",
            "name"  => "YouTube Video Tags",
            "description"  => "Tell us about the amazing ones in the video",
            "prompt"  => "Generate ##results## ##tone## youtube tags in ##lang## on the video title \"##input1##\""
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 6,
            "unique_slug"  => "youtube_video_outline",
            "name"  => "YouTube Video Outline",
            "description"  => "Generate youtube video outline from a video title",
            "prompt"  => "Generate ##results## ##tone## youtube outline (introduction to conclusion) in ##lang## on the video title \"##input1##\""
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 6,
            "unique_slug"  => "youtube_video_intro",
            "name"  => "YouTube Video Intro",
            "description"  => "Write interesting intro script for your youtube video",
            "prompt"  => "Write ##results## ##tone## youtube video introduction in ##lang## on the channel \"##input1##\" and video title ##input2##"
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 6,
            "unique_slug"  => "youtube_video_ideas",
            "name"  => "YouTube Video Ideas",
            "description"  => "Create your video content with the help of AI",
            "prompt"  => "Write ##results## ##tone## video ideas in ##lang## on the video topic \"##input1##\""
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 6,
            "unique_slug"  => "youtube_short_script",
            "name"  => "YouTube Short Script",
            "description"  => "Create the perfect script for your next viral Youtube Shorts.",
            "prompt"  => "Write ##results## ##tone## youtube shorts script in ##lang## on the title \"##input1##\""
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 6,
            "unique_slug"  => "youtube_video_description",
            "name"  => "YouTube Video Description",
            "description"  => "Create your video content with the help of AI",
            "prompt"  => "Write ##results## ##tone## video description in ##lang## on the video content \"##input1##\""
        ]);

        // 7
        DB::table('custom_templates')->insert([
            "category_id" => 7,
            "unique_slug"  => "service_review",
            "name"  => "Service Review",
            "description"  => "Write Service Reviews based on the provider name and service name",
            "prompt"  => "Write ##results## ##tone## service reviews in ##lang## on the service provider name \"##input1##\" and service name ##input2##"
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 7,
            "unique_slug"  => "product_review",
            "name"  => "Product Review",
            "description"  => "Greatest approach to guarantee that you get and promote review of the highest caliber.",
            "prompt"  => "Write ##results## ##tone## product reviews in ##lang## on the store name \"##input1##\" and product name ##input2##"
        ]);

        // 8
        DB::table('custom_templates')->insert([
            "category_id" => 8,
            "unique_slug"  => "product_name",
            "name"  => "Product Name",
            "description"  => "Generate short, catchy names with a state of the art language model",
            "prompt"  => "Write ##results## ##tone## product name in ##lang## on the product description \"##input1##\" and using keywords ##input2##"
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 8,
            "unique_slug"  => "product_description",
            "name"  => "Product Description",
            "description"  => "That enables you to create beautiful and effective product descriptions that sell.",
            "prompt"  => "Write ##results## ##tone## product description in ##lang## on the product name \"##input1##\" and using keywords ##input2##"
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 8,
            "unique_slug"  => "startup_name",
            "name"  => "Startup Name",
            "description"  => "Generate a short, brandable business / startup name using artificial intelligence",
            "prompt"  => "Write ##results## ##tone## startup / business name in ##lang## on the business model \"##input1##\" and using keywords ##input2##"
        ]);

        // 9
        DB::table('custom_templates')->insert([
            "category_id" => 9,
            "unique_slug"  => "google_ads",
            "name"  => "Google Ads",
            "description"  => "Generate Google Ads descriptions in within seconds",
            "prompt"  => "Write ##results## ##tone## best google ads heading and description in ##lang## based on this company / brand name \"##input1##\""
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 9,
            "unique_slug"  => "aida",
            "name"  => "AIDA framework",
            "description"  => "The best-known marketing model for tracing the customer journey",
            "prompt"  => "Write ##results## ##tone## Attention, Interest, Desire and Action AIDA Framework content in ##lang## based on this company / brand name \"##input1##\" and product / service ##input2##"
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 9,
            "unique_slug"  => "generate_by_website_url",
            "name"  => "Generate by Website URL",
            "description"  => "Generate mete title, meta description and keywords from a given website url.",
            "prompt"  => "Use this website url ##input1## and website description ##input2## to create ##lang## a suitable meta name, meta description and keywords."
        ]);

        DB::table('custom_templates')->insert([
            "category_id" => 9,
            "unique_slug"  => "custom_prompt",
            "name"  => "Custom Prompt",
            "description"  => "Ask AI about anything that attracts others content",
            "prompt"  => "Write content ##results## in ##lang## based on this topic ##tone## \"##input1##\""
        ]);
    }
}
