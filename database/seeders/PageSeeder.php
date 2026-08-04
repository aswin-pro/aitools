<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Classic Theme Content

        // Home page
        DB::table('pages')->insert([
            'theme_id' => '513402991882314',
            'name' => 'Home',
            'title' => 'Content',
            'slug' => 'home',
            'body' => '<div class="relative bg-gray-50 pt-20 md:pt-10 pb-24 lg:py-20">
            <div class="mx-auto lg:px-48 xl:px-48 2xl:px-80 px-6">
              <div class="flex flex-wrap -mx-4">
                <div class="w-full lg:w-1/2 px-4 flex items-center">
                  <div class="w-full text-center lg:text-left">
                    <div
                      class="relative max-w-md mx-auto lg:mx-0"
                      data-aos="fade-up"
                    >
                      <h2 class="mb-3 text-4xl lg:text-5xl font-black font-heading">
                        <span>Ultimate Solution for Content Creation!</span>
                      </h2>
                    </div>
                    <div
                      class="relative max-w-sm mx-auto lg:mx-0"
                      data-aos="fade-up"
                    >
                      <p class="mb-6 text-gray-400 leading-loose">
                        Generate Engaging and High-Quality Content in Minutes with the Help of AI.
                      </p>
                      <div>
                        <a
                          class="inline-block mb-3 lg:mb-0 lg:mr-3 w-full lg:w-auto py-2 px-6 leading-loose bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-l-xl rounded-t-xl transition duration-200"
                          href="../../register"
                          >Sign Up</a
                        >
                        <a
                          class="inline-block w-full lg:w-auto py-2 px-6 mr-2 leading-loose font-semibold text-gray-50 bg-gray-500 hover:bg-gray-600 rounded-l-xl rounded-t-xl transition duration-200"
                          href="/features"
                          >More features</a
                        >
                      </div>
                    </div>
                  </div>
                </div>
                <div class="w-full lg:w-1/2">
                  <div class="flex flex-wrap -mx-4">
                    <img
                      class="lg:absolute top-0 my-12 lg:my-0 h-full w-full lg:w-1/3 rounded-3xl lg:rounded-none md:inline-flex"
                      src="../../images/web/background/slider.svg"
                      alt="AI Tools"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          ',
            'page_title' => 'Home - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        // Features page
        DB::table('pages')->insert([
            'theme_id' => '513402991882314',
            'name' => 'Features',
            'title' => 'Content',
            'slug' => 'features',
            'body' => '<section class="py-24 md:pb-32 bg-white" style="background-image: url("flex-ui-assets/elements/pattern-white.svg"); background-position: center;"><div class="container mx-auto px-4"><div class="md:max-w-4xl mb-12 mx-auto text-center" data-aos="fade-up"><span class="inline-block py-px px-2 mb-4 text-xs leading-5 text-yellow-500 bg-yellow-100 font-medium uppercase rounded-full shadow-sm">#1 Fastest growing content creator</span><h1 class="mb-4 text-3xl md:text-4xl leading-tight font-bold tracking-tighter">Main Features</h1></div><div class="flex flex-wrap -mx-4" data-aos="fade-up" data-aos-delay="100"><div class="w-full md:w-1/2 lg:w-1/3 px-4 py-2"><div class="h-full p-8 text-center hover:bg-white rounded-md shadow-md hover:shadow-xl transition duration-200"><div class="inline-flex h-16 w-16 mb-6 mx-auto items-center justify-center text-white bg-yellow-500 rounded-lg"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGNsYXNzPSJpY29uIGljb24tdGFibGVyIGljb24tdGFibGVyLWFydGljbGUiIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZT0iY3VycmVudENvbG9yIiBmaWxsPSJub25lIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPgogIDxwYXRoIHN0cm9rZT0ibm9uZSIgZD0iTTAgMGgyNHYyNEgweiIgZmlsbD0ibm9uZSIvPgogIDxwYXRoIGQ9Ik0zIDRtMCAyYTIgMiAwIDAgMSAyIC0yaDE0YTIgMiAwIDAgMSAyIDJ2MTJhMiAyIDAgMCAxIC0yIDJoLTE0YTIgMiAwIDAgMSAtMiAtMnoiIC8+CiAgPHBhdGggZD0iTTcgOGgxMCIgLz4KICA8cGF0aCBkPSJNNyAxMmgxMCIgLz4KICA8cGF0aCBkPSJNNyAxNmgxMCIgLz4KPC9zdmc+CgoK"></div><h3 class="mb-4 text-xl md:text-2xl leading-tight font-bold">Automated Content Generation</h3><p class="text-coolGray-500 font-medium">AI content creation uses machine learning algorithms to automatically generate high-quality, engaging content across various mediums, including articles, blogs, social media posts, and more.</p></div></div><div class="w-full md:w-1/2 lg:w-1/3 px-4 py-2"><div class="h-full p-8 text-center hover:bg-white rounded-md shadow-md hover:shadow-xl transition duration-200"><div class="inline-flex h-16 w-16 mb-6 mx-auto items-center justify-center text-white bg-yellow-500 rounded-lg"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGNsYXNzPSJpY29uIGljb24tdGFibGVyIGljb24tdGFibGVyLWFydGljbGUiIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZT0iY3VycmVudENvbG9yIiBmaWxsPSJub25lIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPgogIDxwYXRoIHN0cm9rZT0ibm9uZSIgZD0iTTAgMGgyNHYyNEgweiIgZmlsbD0ibm9uZSIvPgogIDxwYXRoIGQ9Ik0zIDRtMCAyYTIgMiAwIDAgMSAyIC0yaDE0YTIgMiAwIDAgMSAyIDJ2MTJhMiAyIDAgMCAxIC0yIDJoLTE0YTIgMiAwIDAgMSAtMiAtMnoiIC8+CiAgPHBhdGggZD0iTTcgOGgxMCIgLz4KICA8cGF0aCBkPSJNNyAxMmgxMCIgLz4KICA8cGF0aCBkPSJNNyAxNmgxMCIgLz4KPC9zdmc+CgoK"></div><h3 class="mb-4 text-xl md:text-2xl leading-tight font-bold">Customizable Tone and Style</h3><p class="text-coolGray-500 font-medium">The content generated by AI can be tailored to match the tone and style of your brand, making it easier to maintain consistency across all of your content.</p></div></div><div class="w-full md:w-1/2 lg:w-1/3 px-4 py-2"><div class="h-full p-8 text-center hover:bg-white rounded-md shadow-md hover:shadow-xl transition duration-200"><div class="inline-flex h-16 w-16 mb-6 mx-auto items-center justify-center text-white bg-yellow-500 rounded-lg"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGNsYXNzPSJpY29uIGljb24tdGFibGVyIGljb24tdGFibGVyLWFydGljbGUiIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZT0iY3VycmVudENvbG9yIiBmaWxsPSJub25lIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPgogIDxwYXRoIHN0cm9rZT0ibm9uZSIgZD0iTTAgMGgyNHYyNEgweiIgZmlsbD0ibm9uZSIvPgogIDxwYXRoIGQ9Ik0zIDRtMCAyYTIgMiAwIDAgMSAyIC0yaDE0YTIgMiAwIDAgMSAyIDJ2MTJhMiAyIDAgMCAxIC0yIDJoLTE0YTIgMiAwIDAgMSAtMiAtMnoiIC8+CiAgPHBhdGggZD0iTTcgOGgxMCIgLz4KICA8cGF0aCBkPSJNNyAxMmgxMCIgLz4KICA8cGF0aCBkPSJNNyAxNmgxMCIgLz4KPC9zdmc+CgoK"></div><h3 class="mb-4 text-xl md:text-2xl leading-tight font-bold">Multi-language Support</h3><p class="text-coolGray-500 font-medium">AI Tools support multiple languages, enabling users to generate content in different languages to cater to a global audience or reach specific markets.</p></div></div><div class="w-full md:w-1/2 lg:w-1/3 px-4 py-2"><div class="h-full p-8 text-center hover:bg-white rounded-md shadow-md hover:shadow-xl transition duration-200"><div class="inline-flex h-16 w-16 mb-6 mx-auto items-center justify-center text-white bg-yellow-500 rounded-lg"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGNsYXNzPSJpY29uIGljb24tdGFibGVyIGljb24tdGFibGVyLWFydGljbGUiIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZT0iY3VycmVudENvbG9yIiBmaWxsPSJub25lIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPgogIDxwYXRoIHN0cm9rZT0ibm9uZSIgZD0iTTAgMGgyNHYyNEgweiIgZmlsbD0ibm9uZSIvPgogIDxwYXRoIGQ9Ik0zIDRtMCAyYTIgMiAwIDAgMSAyIC0yaDE0YTIgMiAwIDAgMSAyIDJ2MTJhMiAyIDAgMCAxIC0yIDJoLTE0YTIgMiAwIDAgMSAtMiAtMnoiIC8+CiAgPHBhdGggZD0iTTcgOGgxMCIgLz4KICA8cGF0aCBkPSJNNyAxMmgxMCIgLz4KICA8cGF0aCBkPSJNNyAxNmgxMCIgLz4KPC9zdmc+CgoK"></div><h3 class="mb-4 text-xl md:text-2xl leading-tight font-bold">SEO Optimization</h3><p class="text-coolGray-500 font-medium">AI content creation can optimize content for search engines, improving the visibility and ranking of your content in search results.</p></div></div><div class="w-full md:w-1/2 lg:w-1/3 px-4 py-2"><div class="h-full p-8 text-center hover:bg-white rounded-md shadow-md hover:shadow-xl transition duration-200"><div class="inline-flex h-16 w-16 mb-6 mx-auto items-center justify-center text-white bg-yellow-500 rounded-lg"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGNsYXNzPSJpY29uIGljb24tdGFibGVyIGljb24tdGFibGVyLWFydGljbGUiIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZT0iY3VycmVudENvbG9yIiBmaWxsPSJub25lIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPgogIDxwYXRoIHN0cm9rZT0ibm9uZSIgZD0iTTAgMGgyNHYyNEgweiIgZmlsbD0ibm9uZSIvPgogIDxwYXRoIGQ9Ik0zIDRtMCAyYTIgMiAwIDAgMSAyIC0yaDE0YTIgMiAwIDAgMSAyIDJ2MTJhMiAyIDAgMCAxIC0yIDJoLTE0YTIgMiAwIDAgMSAtMiAtMnoiIC8+CiAgPHBhdGggZD0iTTcgOGgxMCIgLz4KICA8cGF0aCBkPSJNNyAxMmgxMCIgLz4KICA8cGF0aCBkPSJNNyAxNmgxMCIgLz4KPC9zdmc+CgoK"></div><h3 class="mb-4 text-xl md:text-2xl leading-tight font-bold">Topic Research</h3><p class="text-coolGray-500 font-medium">AI content creation can generate ideas and topic suggestions for content creation, based on user input and analysis of current trends and popular search terms.</p></div></div><div class="w-full md:w-1/2 lg:w-1/3 px-4 py-2"><div class="h-full p-8 text-center hover:bg-white rounded-md shadow-md hover:shadow-xl transition duration-200"><div class="inline-flex h-16 w-16 mb-6 mx-auto items-center justify-center text-white bg-yellow-500 rounded-lg"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGNsYXNzPSJpY29uIGljb24tdGFibGVyIGljb24tdGFibGVyLWFydGljbGUiIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZT0iY3VycmVudENvbG9yIiBmaWxsPSJub25lIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPgogIDxwYXRoIHN0cm9rZT0ibm9uZSIgZD0iTTAgMGgyNHYyNEgweiIgZmlsbD0ibm9uZSIvPgogIDxwYXRoIGQ9Ik0zIDRtMCAyYTIgMiAwIDAgMSAyIC0yaDE0YTIgMiAwIDAgMSAyIDJ2MTJhMiAyIDAgMCAxIC0yIDJoLTE0YTIgMiAwIDAgMSAtMiAtMnoiIC8+CiAgPHBhdGggZD0iTTcgOGgxMCIgLz4KICA8cGF0aCBkPSJNNyAxMmgxMCIgLz4KICA8cGF0aCBkPSJNNyAxNmgxMCIgLz4KPC9zdmc+CgoK"></div><h3 class="mb-4 text-xl md:text-2xl leading-tight font-bold">Content Analysis</h3><p class="text-coolGray-500 font-medium">AI content creation can analyze the performance and engagement of content, using data and metrics to improve future content creation.</p></div></div></div></div></section><section class="py-20 bg-gray-50" data-aos="fade-up" data-aos-delay="300"><div class="container mx-auto"><div class="flex flex-wrap items-center justify-center"><div class="w-auto mb-10 lg:mb-0 lg:mr-8 py-8 px-2 rounded"><img class="h-12" src="../../images/web/logo/logo.png" alt=""></div><div class="w-full lg:w-auto mb-10 lg:mb-0 text-center lg:text-left"><h2 class="max-w-xl mx-auto lg:mx-0 mb-2 text-4xl lg:text-5xl font-bold font-heading">Many useful features</h2><p class="max-w-xl mx-auto lg:mx-0 text-gray-500 leading-loose">With AI Content Creation, you can generate high-quality, engaging content across various mediums, including articles, blogs, social media posts, and more, with ease.</p></div><div class="w-full lg:w-auto lg:ml-auto text-center"><a class="inline-block py-2 px-6 mx-24 bg-yellow-500 hover:bg-yellow-600 text-white font-bold leading-loose rounded-l-xl rounded-t-xl transition duration-200" href="../../register">Register</a></div></div></div></section>',
            'page_title' => 'Features - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        // Tools page
        DB::table('pages')->insert([
            'theme_id' => '513402991882314',
            'name' => 'Tools',
            'title' => 'Content',
            'slug' => 'tools',
            'body' => '<div class="md:max-w-4xl mb-12 mx-auto text-center" data-aos="fade-up"><span class="inline-block py-px px-2 mb-4 text-xs leading-5 text-yellow-500 bg-yellow-100 font-medium uppercase rounded-full shadow-sm">Tools</span>
            <h1 class="mb-4 text-3xl md:text-4xl leading-tight font-bold tracking-tighter">Transform Your Content Creation with AI</h1>
            <p class="text-lg md:text-xl text-coolGray-500 font-medium">Create high-quality content faster and easier than ever before using the power of artificial intelligence technology.</p>
            </div>',
            'page_title' => 'Tools - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        // About page
        DB::table('pages')->insert([
            'theme_id' => '513402991882314',
            'name' => 'About',
            'title' => 'Content',
            'slug' => 'about',
            'body' => '<section class="py-20 xl:pt-24 xl:pb-28 bg-white" style="background-image: url("../../images/web/elements/pattern-white.svg"); background-position: center;">
            <div class="container px-4 mx-auto">
            <div class="flex flex-wrap -mx-4">
            <div class="w-full lg:w-1/2 px-4 mb-5 lg:mb-0" data-aos="fade-up"><span class="inline-block py-px px-2 mb-4 text-xs leading-5 text-yellow-500 bg-yellow-100 font-medium uppercase rounded-9xl">About Us</span>
            <h3 class="mb-5 text-3xl md:text-4xl text-gray-900 font-bold tracking-tighter">Revolutionizing Content Creation with AI-Powered Tools</h3>
            <p class="mb-5 text-lg font-medium leading-7 text-gray-500"><span style="font-size: 12pt;"><strong>At AI Tools</strong>, </span>we believe that creating high-quality content should be easy and accessible for everyone. That is why we will developed an AI-powered content creation tool that automates the content creation process and generates engaging content across various mediums, including articles, blogs, social media posts, and more.</p>
            <p class="mb-5 text-lg font-medium leading-7 text-gray-500">Our team consists of experts in machine learning, natural language processing, and content creation who work tirelessly to ensure that our product is user-friendly, effective, and reliable. We are committed to using AI technology to make content creation more efficient and effective for our users.</p>
            <p class="mb-5 text-lg font-medium leading-7 text-gray-500">With AI Tools Content Creation, you can customize the tone and style of your content to match your brand, optimize your content for search engines, and generate topic suggestions based on current trends and popular search terms. Our machine learning algorithms analyze your input and use natural language processing to generate content that matches the tone and style of your brand.</p>
            </div>
            <div class="w-full lg:w-1/2 px-4" data-aos="fade-up" data-aos-delay="100">
            <p class="mb-5 text-lg font-medium leading-7 text-gray-500">We are also dedicated to providing exceptional customer service and support to help our clients achieve their content creation goals. Our team is always available to answer any questions, provide guidance, and troubleshoot any issues that may arise.</p>
            <p class="mb-5 text-lg font-medium leading-7 text-gray-500">In addition, AI Tools Content Creation is support multiple languages, enabling users to generate content in different languages to cater to a global audience or reach specific markets.</p>
            <p class="mb-5 text-lg font-medium leading-7 text-gray-500">At AI Tools, we are passionate about using AI technology to make content creation more accessible and efficient for everyone. Our product is constantly evolving and improving to meet the needs of our users. We are excited to continue innovating in the content creation space and helping our users create exceptional content with ease.</p>
            <p class="text-lg font-medium leading-7 text-gray-500">We understand that creating content can be time-consuming and challenging, which is why we will designed our tool to be user-friendly, effective, and reliable. Whether you are a seasoned content creator or just getting started, our AI-powered content creation tool can help you streamline the content creation process and produce exceptional content that resonates with your audience.</p>
            </div>
            </div>
            </div>
            </section>',
            'page_title' => 'About - AI Tools - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        // Pricing page
        DB::table('pages')->insert([
            'theme_id' => '513402991882314',
            'name' => 'Pricing',
            'title' => 'Content',
            'slug' => 'pricing',
            'body' => '<div class="text-center" data-aos="fade-up"><span class="inline-block py-px px-2 mb-4 text-xs leading-5 text-yellow-500 bg-yellow-100 font-medium uppercase rounded-9xl">Pricing</span>
            <h3 class="mb-4 text-3xl md:text-5xl text-gray-900 font-bold tracking-tighter">Flexible pricing plan for your startup</h3>
            <p class="mb-12 text-lg md:text-xl text-gray-500 font-medium">Pricing that scales with your business immediately.</p>
            </div>',
            'page_title' => 'Pricing - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '513402991882314',
            'name' => 'Pricing',
            'title' => 'Content',
            'slug' => 'pricing',
            'body' => '<div class="relative -mb-40 py-16 px-4 md:px-8 lg:px-16 bg-gray-900 rounded-xl overflow-hidden" style="background-image: url("../../images/web/elements/pattern-dark.svg"); background-position: center;" data-aos="fade-up">
            <div class="relative max-w-max mx-auto text-center">
            <h3 class="mb-2 text-2xl md:text-5xl leading-tight font-bold text-white tracking-tighter">If you need unique features</h3>
            <p class="mb-6 text-base md:text-xl text-gray-400">Speak with our team of experienced content creation experts to get advice and feedback on your content. Whether you are struggling to find the right words or need help optimizing your content for SEO, our experts are here to help.</p>
            <a class="inline-block mb-3 lg:mb-0 lg:mr-3 w-1/3 lg:full py-2 px-6 leading-loose bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-l-xl rounded-t-xl transition duration-200 text-center" href="../../contact">Get in touch</a></div>
            </div>',
            'page_title' => 'Pricing - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        // Contact page
        DB::table('pages')->insert([
            'theme_id' => '513402991882314',
            'name' => 'Contact us',
            'title' => 'Content',
            'slug' => 'contact',
            'body' => '<div class="flex flex-wrap mb-24 lg:mb-18 justify-between items-center" data-aos="fade-up">
            <div class="w-full lg:w-1/2 mb-10 lg:mb-0">
            <span class="inline-block py-px px-2 mb-4 text-xs leading-5 text-yellow-500 bg-yellow-100 font-medium uppercase rounded-9xl">Contact us</span>
            <h3 class="mb-4 text-4xl md:text-5xl text-darkgray-900 font-bold tracking-tighter leading-tight">
            Let’s stay connected</h3>
            <p class="text-lg md:text-xl text-gray-500 font-medium">It is never been easier to get in touch with Flex. Call us, use our live chat widget or email and we will get back to you as soon as possible!</p>
            </div>
            <div class="w-full lg:w-auto">
            <div class="flex flex-wrap justify-center items-center md:justify-start -mb-2"><a class="inline-block mb-3 lg:mb-0 lg:mr-3 w-full lg:full py-2 px-6 leading-loose bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-l-xl rounded-t-xl transition duration-200 text-center" href="/about">About Us</a></div>
            </div>
            </div>',
            'page_title' => 'Contact us - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '513402991882314',
            'name' => 'Contact us',
            'title' => 'Content',
            'slug' => 'contact',
            'body' => '<div class="w-full lg:w-1/2 px-4 mb-14 lg:mb-0">
            <div class="flex flex-wrap -mx-4" data-aos="fade-up" data-aos-delay="100">
            <div class="w-full md:w-1/2 px-4 mb-10">
            <div class="max-w-xs mx-auto">
            <div class="inline-flex mb-6 items-center justify-center w-12 h-12 bg-yellow-500 rounded-full"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGNsYXNzPSJpY29uIGljb24tdGFibGVyIGljb24tdGFibGVyLW1haWwtb3BlbmVkIiB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgZmlsbD0ibm9uZSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj4KICA8cGF0aCBzdHJva2U9Im5vbmUiIGQ9Ik0wIDBoMjR2MjRIMHoiIGZpbGw9Im5vbmUiLz4KICA8cGF0aCBkPSJNMyA5bDkgNmw5IC02bC05IC02bC05IDYiIC8+CiAgPHBhdGggZD0iTTIxIDl2MTBhMiAyIDAgMCAxIC0yIDJoLTE0YTIgMiAwIDAgMSAtMiAtMnYtMTAiIC8+CiAgPHBhdGggZD0iTTMgMTlsNiAtNiIgLz4KICA8cGF0aCBkPSJNMTUgMTNsNiA2IiAvPgo8L3N2Zz4KCgo="></div>
            <h3 class="mb-4 text-2xl md:text-3xl font-bold leading-9 text-gray-900">Email</h3>
            <a class="text-lg md:text-xl text-gray-500 hover:text-gray-500 font-medium" href="mailto:contact@aitools.com">contact@aitools.com</a></div>
            </div>
            <div class="w-full md:w-1/2 px-4 mb-10">
            <div class="max-w-xs mx-auto">
            <div class="inline-flex mb-6 items-center justify-center w-12 h-12 bg-yellow-500 rounded-full"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGNsYXNzPSJpY29uIGljb24tdGFibGVyIGljb24tdGFibGVyLXBob25lIiB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgZmlsbD0ibm9uZSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj4KICA8cGF0aCBzdHJva2U9Im5vbmUiIGQ9Ik0wIDBoMjR2MjRIMHoiIGZpbGw9Im5vbmUiLz4KICA8cGF0aCBkPSJNNSA0aDRsMiA1bC0yLjUgMS41YTExIDExIDAgMCAwIDUgNWwxLjUgLTIuNWw1IDJ2NGEyIDIgMCAwIDEgLTIgMmExNiAxNiAwIDAgMSAtMTUgLTE1YTIgMiAwIDAgMSAyIC0yIiAvPgo8L3N2Zz4KCgo="></div>
            <h3 class="mb-4 text-2xl md:text-3xl font-bold leading-9 text-gray-900">Phone</h3>
            <p class="text-lg md:text-xl text-gray-500 font-medium">+91 987-654-3210</p>
            </div>
            </div>
            <div class="w-full md:w-1/2 px-4 mb-10 md:mb-0">
            <div class="max-w-xs mx-auto">
            <div class="inline-flex mb-6 items-center justify-center w-12 h-12 bg-yellow-500 rounded-full"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGNsYXNzPSJpY29uIGljb24tdGFibGVyIGljb24tdGFibGVyLW1hcC1waW4tZmlsbGVkIiB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgZmlsbD0ibm9uZSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj4KICA8cGF0aCBzdHJva2U9Im5vbmUiIGQ9Ik0wIDBoMjR2MjRIMHoiIGZpbGw9Im5vbmUiLz4KICA8cGF0aCBkPSJNMTguMzY0IDQuNjM2YTkgOSAwIDAgMSAuMjAzIDEyLjUxOWwtLjIwMyAuMjFsLTQuMjQzIDQuMjQyYTMgMyAwIDAgMSAtNC4wOTcgLjEzNWwtLjE0NCAtLjEzNWwtNC4yNDQgLTQuMjQzYTkgOSAwIDAgMSAxMi43MjggLTEyLjcyOHptLTYuMzY0IDMuMzY0YTMgMyAwIDEgMCAwIDZhMyAzIDAgMCAwIDAgLTZ6IiBzdHJva2Utd2lkdGg9IjAiIGZpbGw9ImN1cnJlbnRDb2xvciIgLz4KPC9zdmc+CgoK"></div>
            <h3 class="mb-4 text-2xl md:text-3xl font-bold leading-9 text-gray-900">Office</h3>
            <p class="text-lg md:text-xl text-gray-500 font-medium">1686, Geraldine Lane</p>
            <p class="text-lg md:text-xl text-gray-500 font-medium">New York, NY 10013</p>
            </div>
            </div>
            <div class="w-full md:w-1/2 px-4">
            <div class="max-w-xs mx-auto">
            <div class="inline-flex mb-6 items-center justify-center w-12 h-12 bg-yellow-500 rounded-full"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGNsYXNzPSJpY29uIGljb24tdGFibGVyIGljb24tdGFibGVyLWJ1aWxkaW5nIiB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgZmlsbD0ibm9uZSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj4KICA8cGF0aCBzdHJva2U9Im5vbmUiIGQ9Ik0wIDBoMjR2MjRIMHoiIGZpbGw9Im5vbmUiLz4KICA8cGF0aCBkPSJNMyAyMWwxOCAwIiAvPgogIDxwYXRoIGQ9Ik05IDhsMSAwIiAvPgogIDxwYXRoIGQ9Ik05IDEybDEgMCIgLz4KICA8cGF0aCBkPSJNOSAxNmwxIDAiIC8+CiAgPHBhdGggZD0iTTE0IDhsMSAwIiAvPgogIDxwYXRoIGQ9Ik0xNCAxMmwxIDAiIC8+CiAgPHBhdGggZD0iTTE0IDE2bDEgMCIgLz4KICA8cGF0aCBkPSJNNSAyMXYtMTZhMiAyIDAgMCAxIDIgLTJoMTBhMiAyIDAgMCAxIDIgMnYxNiIgLz4KPC9zdmc+CgoK"></div>
            <h3 class="mb-4 text-2xl md:text-3xl font-bold leading-9 text-gray-900">Socials</h3>
            <a class="inline-block mr-8 text-yellow-500 hover:text-yellow-500" href="#">
            <div class="max-w-xs mx-auto"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGNsYXNzPSJpY29uIGljb24tdGFibGVyIGljb24tdGFibGVyLWJyYW5kLWZhY2Vib29rIiB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgZmlsbD0ibm9uZSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj4KICA8cGF0aCBzdHJva2U9Im5vbmUiIGQ9Ik0wIDBoMjR2MjRIMHoiIGZpbGw9Im5vbmUiLz4KICA8cGF0aCBkPSJNNyAxMHY0aDN2N2g0di03aDNsMSAtNGgtNHYtMmExIDEgMCAwIDEgMSAtMWgzdi00aC0zYTUgNSAwIDAgMCAtNSA1djJoLTMiIC8+Cjwvc3ZnPgoKCg=="></div>
            </a> <a class="inline-block mr-8 text-yellow-500 hover:text-yellow-500" href="#">
            <div class="max-w-xs mx-auto"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGNsYXNzPSJpY29uIGljb24tdGFibGVyIGljb24tdGFibGVyLWJyYW5kLXR3aXR0ZXIiIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZT0iY3VycmVudENvbG9yIiBmaWxsPSJub25lIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPgogIDxwYXRoIHN0cm9rZT0ibm9uZSIgZD0iTTAgMGgyNHYyNEgweiIgZmlsbD0ibm9uZSIvPgogIDxwYXRoIGQ9Ik0yMiA0LjAxYy0xIC40OSAtMS45OCAuNjg5IC0zIC45OWMtMS4xMjEgLTEuMjY1IC0yLjc4MyAtMS4zMzUgLTQuMzggLS43MzdzLTIuNjQzIDIuMDYgLTIuNjIgMy43Mzd2MWMtMy4yNDUgLjA4MyAtNi4xMzUgLTEuMzk1IC04IC00YzAgMCAtNC4xODIgNy40MzMgNCAxMWMtMS44NzIgMS4yNDcgLTMuNzM5IDIuMDg4IC02IDJjMy4zMDggMS44MDMgNi45MTMgMi40MjMgMTAuMDM0IDEuNTE3YzMuNTggLTEuMDQgNi41MjIgLTMuNzIzIDcuNjUxIC03Ljc0MmExMy44NCAxMy44NCAwIDAgMCAuNDk3IC0zLjc1M2MwIC0uMjQ5IDEuNTEgLTIuNzcyIDEuODE4IC00LjAxM3oiIC8+Cjwvc3ZnPgoKCg=="></div>
            </a> <a class="inline-block mr-8 text-yellow-500 hover:text-yellow-500" href="#">
            <div class="max-w-xs mx-auto"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGNsYXNzPSJpY29uIGljb24tdGFibGVyIGljb24tdGFibGVyLWJyYW5kLWluc3RhZ3JhbSIgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiB2aWV3Qm94PSIwIDAgMjQgMjQiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlPSJjdXJyZW50Q29sb3IiIGZpbGw9Im5vbmUiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCI+CiAgPHBhdGggc3Ryb2tlPSJub25lIiBkPSJNMCAwaDI0djI0SDB6IiBmaWxsPSJub25lIi8+CiAgPHBhdGggZD0iTTQgNG0wIDRhNCA0IDAgMCAxIDQgLTRoOGE0IDQgMCAwIDEgNCA0djhhNCA0IDAgMCAxIC00IDRoLThhNCA0IDAgMCAxIC00IC00eiIgLz4KICA8cGF0aCBkPSJNMTIgMTJtLTMgMGEzIDMgMCAxIDAgNiAwYTMgMyAwIDEgMCAtNiAwIiAvPgogIDxwYXRoIGQ9Ik0xNi41IDcuNWwwIC4wMSIgLz4KPC9zdmc+CgoK"></div>
            </a> <a class="inline-block text-yellow-500 hover:text-yellow-500" href="#">
            <div class="max-w-xs mx-auto"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGNsYXNzPSJpY29uIGljb24tdGFibGVyIGljb24tdGFibGVyLWJyYW5kLWxpbmtlZGluIiB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgZmlsbD0ibm9uZSIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj4KICA8cGF0aCBzdHJva2U9Im5vbmUiIGQ9Ik0wIDBoMjR2MjRIMHoiIGZpbGw9Im5vbmUiLz4KICA8cGF0aCBkPSJNNCA0bTAgMmEyIDIgMCAwIDEgMiAtMmgxMmEyIDIgMCAwIDEgMiAydjEyYTIgMiAwIDAgMSAtMiAyaC0xMmEyIDIgMCAwIDEgLTIgLTJ6IiAvPgogIDxwYXRoIGQ9Ik04IDExbDAgNSIgLz4KICA8cGF0aCBkPSJNOCA4bDAgLjAxIiAvPgogIDxwYXRoIGQ9Ik0xMiAxNmwwIC01IiAvPgogIDxwYXRoIGQ9Ik0xNiAxNnYtM2EyIDIgMCAwIDAgLTQgMCIgLz4KPC9zdmc+CgoK"></div>
            </a></div>
            </div>
            </div>
            </div>',
            'page_title' => 'Contact us - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        // FAQs
        DB::table('pages')->insert([
            'theme_id' => '513402991882314',
            'name' => 'FAQs',
            'title' => 'Content',
            'slug' => 'faq',
            'body' => '<section class="pt-24 bg-white" style="background-image: url("../../images/web/elements/pattern-white.svg"); background-position: center;">
            <div class="container px-4 mx-auto">
            <div class="max-w-4xl mb-16" data-aos="fade-up">
            <span class="inline-block py-px px-2 mb-4 text-xs leading-5 text-yellow-500 bg-yellow-100 font-medium rounded-full shadow-sm">FAQs</span>
            <h2 class="mb-4 text-4xl md:text-5xl leading-tight text-gray-900 font-bold tracking-tighter">Frequently Asked Questions</h2>
            <p class="text-lg md:text-xl text-gray-500 font-medium">AI Tools is the only saas business platform that lets you run your business on one platform, seamlessly across all digital channels.</p>
            </div>
            <div class="flex flex-wrap pb-16 -mx-4" data-aos="fade-up">
            <div class="w-full md:w-1/2 xl:w-1/3 px-4 mb-8" data-aos="fade-up" data-aos-delay="100">
            <div class="md:max-w-xs">
            <div class="inline-flex mb-6 items-center justify-center w-12 h-12 rounded-full bg-yellow-500">
            <img src="../../images/web/elements/shield-icon.svg" alt="">
            </div>
            <h3 class="mb-6 text-xl font-bold text-gray-900">What shipping options do you have?
            </h3>
            <p class="font-medium text-gray-500">For USA domestic orders we offer FedEx and USPS shipping. Contact us via email to learn more.</p>
            </div>
            </div>
            <div class="w-full md:w-1/2 xl:w-1/3 px-4 mb-8" data-aos="fade-up" data-aos-delay="200">
            <div class="md:max-w-xs">
            <div class="inline-flex mb-6 items-center justify-center w-12 h-12 rounded-full bg-yellow-500">
            <img src="../../images/web/elements/shield-icon.svg" alt="">
            </div>
            <h3 class="mb-6 text-xl font-bold text-gray-900">What payment methods do you accept?
            </h3>
            <p class="font-medium text-gray-500">Any method of payments acceptable by you. For example: We accept MasterCard, Visa.</p>
            </div>
            </div>
            <div class="w-full md:w-1/2 xl:w-1/3 px-4 mb-8" data-aos="fade-up" data-aos-delay="300">
            <div class="md:max-w-xs">
            <div class="inline-flex mb-6 items-center justify-center w-12 h-12 rounded-full bg-yellow-500">
            <img src="../../images/web/elements/shield-icon.svg" alt="">
            </div>
            <h3 class="mb-6 text-xl font-bold text-gray-900">How long does it take to ship my order?</h3>
            <p class="font-medium text-gray-500">Orders are usually shipped within 1-2 business days after placing the order.</p>
            </div>
            </div>
            <div class="w-full md:w-1/2 xl:w-1/3 px-4 mb-8 xl:mb-0" data-aos="fade-up" data-aos-delay="400">
            <div class="md:max-w-xs">
            <div class="inline-flex mb-6 items-center justify-center w-12 h-12 rounded-full bg-yellow-500">
            <img src="../../images/web/elements/shield-icon.svg" alt="">
            </div>
            <h3 class="mb-6 text-xl font-bold text-gray-900">What shipping options do you have?
            </h3>
            <p class="font-medium text-gray-500">For USA domestic orders we offer FedEx and USPS shipping. Contact us via email to learn more.</p>
            </div>
            </div>
            <div class="w-full md:w-1/2 xl:w-1/3 px-4 mb-8 md:mb-0" data-aos="fade-up" data-aos-delay="500">
            <div class="md:max-w-xs">
            <div class="inline-flex mb-6 items-center justify-center w-12 h-12 rounded-full bg-yellow-500">
            <img src="../../images/web/elements/shield-icon.svg" alt="">
            </div>
            <h3 class="mb-6 text-xl font-bold text-gray-900">What payment methods do you accept?
            </h3>
            <p class="font-medium text-gray-500">Any method of payments acceptable by you. For example: We accept MasterCard, Visa.</p>
            </div>
            </div>
            <div class="w-full md:w-1/2 xl:w-1/3 px-4" data-aos="fade-up" data-aos-delay="500">
            <div class="md:max-w-xs">
            <div class="inline-flex mb-6 items-center justify-center w-12 h-12 rounded-full bg-yellow-500">
            <img src="../../images/web/elements/shield-icon.svg" alt="">
            </div>
            <h3 class="mb-6 text-xl font-bold text-gray-900">How long does it take to ship my order?</h3>
            <p class="font-medium text-gray-500">Orders are usually shipped within 1-2 business days after placing the order.</p>
            </div>
            </div>
            </div>
            <div class="relative -mb-40 py-16 px-4 md:px-8 lg:px-16 bg-gray-900 rounded-xl overflow-hidden aos-init" data-aos="fade-up" style="background-image: url("../../images/web/elements/pattern-dark.svg"); background-position: center;">
            <div class="relative max-w-max mx-auto text-center">
            <h3 class="mb-2 text-2xl md:text-5xl leading-tight font-bold text-white tracking-tighter">Have any additional questions?</h3>
            <p class="mb-6 text-base md:text-xl text-gray-400">Schedule More Answers with Our AI Content Creation Experts.</p>
            <a class="inline-block mb-3 lg:mb-0 lg:mr-3 w-1/3 lg:full py-2 px-6 leading-loose bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-l-xl rounded-t-xl transition duration-200 text-center" href="#">Get in touch</a>
            </div>
            </div>
            </div>
            <div class="h-64 bg-gray-50"></div>
            </section>',
            'page_title' => 'FAQs - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        // Privacy policy
        DB::table('pages')->insert([
            'theme_id' => '513402991882314',
            'name' => 'Privacy Policy',
            'title' => 'Content',
            'slug' => 'privacy-policy',
            'body' => '<section class="py-20 xl:pt-24 xl:pb-28 bg-white" style="background-image: url("../../images/web/elements/pattern-white.svg"); background-position: center;">
            <div class="container px-4 mx-auto">
            <div class="flex flex-wrap -mx-4">
            <div class="w-full px-4 mb-10" data-aos="fade-up">
            <div class="flex flex-wrap justify-between items-center">
            <div class="w-full md:w-1/2 mb-10 md:mb-0"><span class="inline-block py-px px-2 mb-4 text-xl leading-7 text-yellow-500 bg-yellow-100 font-medium uppercase rounded-9xl">Privacy Policy</span></div>
            <div class="w-full md:w-auto">
            <div class="flex flex-wrap justify-center items-center -mb-2"><a class="inline-block mb-3 lg:mb-0 lg:mr-3 w-full lg:full py-2 px-6 leading-7 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-l-xl rounded-t-xl transition duration-200 text-center" href="#">Updated on : 24-04-2022 20:30 PM</a></div>
            </div>
            </div>
            </div>
            <div class="w-full lg:w-1/2 px-4 mb-5 lg:mb-0" data-aos="fade-up" data-aos-delay="100">
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">At AI Tools Content Creation, we are committed to protecting your privacy and safeguarding your personal information. This Privacy Policy outlines how we collect, use, and protect your information when you use our AI-powered content creation tool.</p>
            <p class="mb-5 text-xl font-medium leading-2 text-gray-800">Collection of Information</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">We collect information that you provide to us when you register for our services or use our content creation tool. This information may include your name, email address, company name, and other contact information. We may also collect information about your use of our tool, including your browsing history, search terms, and other usage data.</p>
            <p class="mb-5 text-xl font-medium leading-7 text-gray-800">Use of Information</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">We use your information to provide you with our content creation services and to improve our product. We may also use your information to send you marketing communications and other updates about our product, but you can opt-out of these communications at any time.</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">We do not sell or rent your personal information to third parties. However, we may share your information with trusted third-party service providers who help us provide our services, such as payment processors, analytics providers, and customer support providers. We require these service providers to safeguard your personal information and only use it for the purposes for which it was provided.</p>
            <p class="mb-5 text-xl font-medium leading-7 text-gray-800">Security</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">We take the security of your personal information seriously and have implemented measures to protect it from unauthorized access, use, or disclosure. We use industry-standard encryption technology to protect your information during transmission, and we store your information in secure data centers with restricted access.</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">However, no method of transmission over the internet or electronic storage is completely secure, and we cannot guarantee the absolute security of your information.</p>
            </div>
            <div class="w-full lg:w-1/2 px-4" data-aos="fade-up" data-aos-delay="200">
            <p class="mb-5 text-xl font-medium leading-2 text-gray-800">Cookies</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">We may use cookies and similar technologies to collect information about your use of our tool and improve our product. You can control the use of cookies through your browser settings, but disabling cookies may limit your ability to use some features of our tool.</p>
            <p class="mb-5 text-xl font-medium leading-2 text-gray-800">Changes to this Policy</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">We may update this Privacy Policy from time to time to reflect changes in our practices or the law. We encourage you to review this Policy periodically to stay informed about how we collect, use, and protect your personal information.</p>
            <p class="mb-5 text-xl font-medium leading-2 text-gray-800">Contact Us</p>
            <p class="text-md font-medium leading-7 text-gray-500">If you have any questions or concerns about this Privacy Policy or our practices, please contact us at support@aitools.com.</p>
            </div>
            </div>
            </div>
            </section>',
            'page_title' => 'Privacy Policy - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        // Refund policy
        DB::table('pages')->insert([
            'theme_id' => '513402991882314',
            'name' => 'Refund Policy',
            'title' => 'Content',
            'slug' => 'refund-policy',
            'body' => '<section class="py-20 xl:pt-24 xl:pb-28 bg-white" style="background-image: url("../../images/web/elements/pattern-white.svg"); background-position: center;">
            <div class="container px-4 mx-auto">
            <div class="flex flex-wrap -mx-4">
            <div class="w-full px-4 mb-10" data-aos="fade-up">
            <div class="flex flex-wrap justify-between items-center">
            <div class="w-full md:w-1/2 mb-10 md:mb-0"><span class="inline-block py-px px-2 mb-4 text-xl leading-7 text-yellow-500 bg-yellow-100 font-medium uppercase rounded-9xl">Refund Policy</span></div>
            <div class="w-full md:w-auto">
            <div class="flex flex-wrap justify-center items-center -mb-2"><a class="inline-block mb-3 lg:mb-0 lg:mr-3 w-full lg:full py-2 px-6 leading-loose bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-l-xl rounded-t-xl transition duration-200 text-center" href="#">Updated on : 23-04-2022 20:30 PM</a></div>
            </div>
            </div>
            </div>
            <div class="w-full lg:w-1/2 px-4 mb-5 lg:mb-0" data-aos="fade-up" data-aos-delay="100">
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">At AI Tools Content Creation, we want our customers to be completely satisfied with our AI-powered content creation tool. If you are not satisfied with our product for any reason, you may be eligible for a refund.</p>
            <p class="mb-5 text-xl font-medium leading-2 text-gray-800"><strong>Refund Eligibility</strong></p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">To be eligible for a refund, you must meet the following conditions:</p>
            <ol class="mb-5 list-decimal list-inside text-md font-medium leading-7 text-gray-500">
            <li><span class="text-md font-medium leading-7 text-gray-500">You have purchased a subscription to our content creation tool within the past 30 days</span></li>
            <li><span class="text-md font-medium leading-7 text-gray-500">You have used our tool and can demonstrate that it did not meet your expectations</span></li>
            <li><span class="text-md font-medium leading-7 text-gray-500">You have contacted our customer support team to try to resolve the issue</span></li>
            </ol>
            <p class="mb-5 text-xl font-medium leading-7 text-gray-800">Refund Process</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">To request a refund, please contact our customer support team at support@aitools.com. We will review your request and may ask for additional information to help us understand the issue. If we determine that you are eligible for a refund, we will process it within 7 business days.</p>
            <p class="mb-5 text-xl font-medium leading-7 text-gray-800">Refund Amount</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">The amount of your refund will depend on the terms of your subscription and the length of time you have used our tool. We may deduct a pro-rated amount based on the amount of time you have used our tool or any discounts or promotions you received at the time of purchase.</p>
            </div>
            <div class="w-full lg:w-1/2 px-4" data-aos="fade-up" data-aos-delay="200">
            <p class="mb-5 text-xl font-medium leading-7 text-gray-800">Exclusions</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">We reserve the right to deny refund requests in certain circumstances, including but not limited to:</p>
            <ol class="mb-5 list-decimal list-inside text-md font-medium leading-7 text-gray-500">
            <li><span class="text-md font-medium leading-7 text-gray-500">Fraudulent or abusive behavior</span></li>
            <li><span class="text-md font-medium leading-7 text-gray-500">Violation of our Terms of Service</span></li>
            <li><span class="text-md font-medium leading-7 text-gray-500">Technical issues outside of our control, such as internet connectivity or device issues</span></li>
            </ol>
            <p class="mb-5 text-xl font-medium leading-2 text-gray-800">Changes to this Policy</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">We may update this Refund Policy from time to time to reflect changes in our practices or the law. We encourage you to review this Policy periodically to stay informed about our refund process.</p>
            <p class="mb-5 text-xl font-medium leading-2 text-gray-800">Contact Us</p>
            <p class="text-md font-medium leading-7 text-gray-500">If you have any questions or concerns about this Refund Policy or our practices, please contact us at support@aitools.com.</p>
            </div>
            </div>
            </div>
            </section>',
            'page_title' => 'Refund Policy - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        // Terms and Conditions
        DB::table('pages')->insert([
            'theme_id' => '513402991882314',
            'name' => 'Terms and Conditions',
            'title' => 'Content',
            'slug' => 'terms-and-conditions',
            'body' => '<section class="py-20 xl:pt-24 xl:pb-28 bg-white" style="background-image: url("../../images/web/elements/pattern-white.svg"); background-position: center;">
            <div class="container px-4 mx-auto">
            <div class="flex flex-wrap -mx-4">
            <div class="w-full px-4 mb-10" data-aos="fade-up">
            <div class="flex flex-wrap justify-between items-center">
            <div class="w-full md:w-1/2 mb-10 md:mb-0"><span class="inline-block py-px px-2 mb-4 text-xl leading-7 text-yellow-500 bg-yellow-100 font-medium uppercase rounded-9xl">Terms and Conditions</span></div>
            <div class="w-full md:w-auto">
            <div class="flex flex-wrap justify-center items-center -mb-2"><a class="inline-block mb-3 lg:mb-0 lg:mr-3 w-full lg:full py-2 px-6 leading-7 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-l-xl rounded-t-xl transition duration-200 text-center" href="#">Updated on : 23-04-2022 20:30 PM</a></div>
            </div>
            </div>
            </div>
            <div class="w-full lg:w-1/2 px-4 mb-5 lg:mb-0" data-aos="fade-up" data-aos-delay="100">
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">Welcome to AI Tools Content Creation. By using our AI-powered content creation tool, you agree to the following terms and conditions. Please read them carefully.</p>
            <p class="mb-5 text-xl font-medium leading-2 text-gray-800">Use of our Tool</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">Our tool<strong> </strong>is designed to assist you in creating content for your personal or commercial use. You may not use our tool for any illegal or unauthorized purpose, including but not limited to copyright infringement or plagiarism.</p>
            <p class="mb-5 text-xl font-medium leading-7 text-gray-800">Account Registration</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">To use our tool, you must create an account and provide accurate and complete information about yourself. You are responsible for maintaining the security of your account and password, and for any activity that occurs under your account.</p>
            <p class="mb-5 text-xl font-medium leading-7 text-gray-800">Intellectual Property</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">Our tool and its content, including but not limited to text, graphics, logos, and software, are the property of AI Tools Content Creation and are protected by copyright, trademark, and other intellectual property laws. You may not use our content without our express written permission.</p>
            <p class="mb-5 text-xl font-medium leading-7 text-gray-800">Privacy</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">We respect your privacy and are committed to protecting your personal information. Please see our Privacy Policy for more information.</p>
            <p class="mb-5 text-xl font-medium leading-7 text-gray-800">Disclaimer of Warranties</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">Our tool is provided "as is" and without warranties of any kind, express or implied, including but not limited to warranties of merchantability, fitness for a particular purpose, and non-infringement. We do not guarantee that our tool will be error-free, uninterrupted, or secure.</p>
            </div>
            <div class="w-full lg:w-1/2 px-4" data-aos="fade-up" data-aos-delay="200">
            <p class="mb-5 text-xl font-medium leading-2 text-gray-800">Limitation of Liability</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">In no event shall AI Tools Content Creation be liable for any direct, indirect, incidental, special, or consequential damages arising out of or in connection with your use of our tool, whether based on warranty, contract, tort, or any other legal theory.</p>
            <p class="mb-5 text-xl font-medium leading-2 text-gray-800">Indemnification</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">You agree to indemnify and hold AI Tools Content Creation and its affiliates, officers, agents, and employees harmless from any claims, damages, or expenses arising out of or in connection with your use of our tool or your violation of these terms and conditions.</p>
            <p class="mb-5 text-xl font-medium leading-2 text-gray-800">Changes to these Terms and Conditions</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">We may update these terms and conditions from time to time to reflect changes in our practices or the law. We encourage you to review these terms and conditions periodically to stay informed about our policies.</p>
            <p class="mb-5 text-xl font-medium leading-2 text-gray-800">Governing Law and Jurisdiction</p>
            <p class="mb-5 text-md font-medium leading-7 text-gray-500">These terms and conditions shall be governed by and construed in accordance with the laws of [insert governing law], and any disputes arising out of or in connection with these terms and conditions shall be resolved in the courts of [insert jurisdiction].</p>
            <p class="mb-5 text-xl font-medium leading-2 text-gray-800">Contact Us</p>
            <p class="text-md font-medium leading-7 text-gray-500">If you have any questions or concerns about these terms and conditions or our practices, please contact us at support@aitools.com.</p>
            </div>
            </div>
            </div>
            </section>',
            'page_title' => 'Terms and Conditions - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        // Modern Theme Content (Dark)
        DB::table('pages')->insert([
            'theme_id' => '330599619570398',
            'name' => 'Hero',
            'title' => 'Hero Section',
            'slug' => 'hero',
            'body' => '<div class="relative pt-16 lg:px-12 pb-12">
                <div class="relative z-10 container px-4 mx-auto">
                    <div class="mb-24 text-center md:max-w-4xl mx-auto"><span
                            class="inline-block mb-2.5 text-sm text-green-400 font-medium tracking-tighter">AITools</span>
                        <h1
                            class="font-heading text-5xl xs:text-6xl md:text-8xl xl:text-10xl font-bold text-gray-900 mb-8 sm:mb-14">
                            <span class="animated-gradient-text">AI Content Magic</span></h1>
                        <p class="mb-10 text-lg text-white md:max-w-sm mx-auto">Effortless content creation with AI Tools Content
                            Creator. Say goodbye to time-consuming writing tasks. Boost your productivity and creativity instantly.
                        </p>
                        <div class="flex flex-wrap justify-center -m-2">
                            <div class="w-auto p-2"><a
                                    class="inline-block px-8 py-4 tracking-tighter border-2 border-green-400 bg-green-400 hover:bg-green-500 text-black focus:ring-4 focus:ring-green-500 focus:ring-opacity-40 rounded-full transition duration-300"
                                    href="register">Start now for free</a></div>
                        </div>
                    </div>
                </div>
            </div>',
            'page_title' => 'Home - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '330599619570398',
            'name' => 'Home',
            'title' => 'Home',
            'slug' => 'home',
            'body' => '<section class="lg:px-12 pb-24 bg-blueGray-950">
                  <div class="container px-4 mx-auto">
                      <div class="text-center"><span
                              class="inline-block mb-4 text-sm text-green-400 font-medium tracking-tighter">Features</span>
                          <h2 class="mb-6 text-7xl lg:text-7xl text-white tracking-8xl md:max-w-md mx-auto">AI Content Creator</h2>
                          <div class="flex flex-wrap -m-4">
                              <div class="w-full md:w-1/2 lg:w-1/4 p-4">
                                  <div
                                      class="relative pt-16 px-10 pb-16 h-full bg-gradient-radial-dark border border-gray-800 border-opacity-30 rounded-5xl">
                                      <div class="relative z-10"><img class="mb-8 mx-auto"
                                              src="../../themes/modern/assets/images/cards/card-image1.png" alt="">
                                          <h3 class="mb-6 text-3xl text-white tracking-3xl">AI Powered Creation</h3>
                                          <p class="text-white text-opacity-60">Generate content effortlessly with advanced AI technology</p>
                                      </div>
                                  </div>
                              </div>
                              <div class="w-full md:w-1/2 lg:w-1/4 p-4">
                                  <div
                                      class="relative pt-16 px-10 pb-16 h-full bg-gradient-radial-dark border border-gray-800 border-opacity-30 rounded-5xl">
                                      <div class="relative z-10"><img class="mb-8 mx-auto"
                                              src="../../themes/modern/assets/images/cards/card-image1.png" alt="">
                                          <h3 class="mb-6 text-3xl text-white tracking-3xl">User-Friendly Interface</h3>
                                          <p class="text-white text-opacity-60">Navigate with ease through an intuitive interface for a smooth user experience</p>
                                      </div>
                                  </div>
                              </div>
                              <div class="w-full md:w-1/2 lg:w-1/4 p-4">
                                  <div
                                      class="relative pt-16 px-10 pb-16 h-full bg-gradient-radial-dark border border-gray-800 border-opacity-30 rounded-5xl">
                                      <div class="relative z-10"><img class="mb-8 mx-auto"
                                              src="../../themes/modern/assets/images/cards/card-image1.png" alt="">
                                          <h3 class="mb-6 text-3xl text-white tracking-3xl">Customizable Models</h3>
                                          <p class="text-white text-opacity-60">Tailor the system to your specific needs for optimal performance</p>
                                      </div>
                                  </div>
                              </div>
                              <div class="w-full md:w-1/2 lg:w-1/4 p-4">
                                  <div
                                      class="relative pt-16 px-10 pb-16 h-full bg-gradient-radial-dark border border-gray-800 border-opacity-30 rounded-5xl">
                                      <div class="relative z-10"><img class="mb-8 mx-auto"
                                              src="../../themes/modern/assets/images/cards/card-image1.png" alt="">
                                          <h3 class="mb-6 text-3xl text-white tracking-3xl">24/7 Customer Support</h3>
                                          <p class="text-white text-opacity-60">Receive dedicated support around the clock for a positive user experience</p>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </section>',
            'page_title' => 'Home - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '330599619570398',
            'name' => 'Newsletter',
            'title' => 'Newsletter',
            'slug' => 'newsletter',
            'body' => '<section class="py-24 overflow-hidden">
                    <div class="container px-4 mx-auto">
                        <div class="py-14 bg-gradient-radial-dark rounded-6xl">
                            <div class="md:max-w-2xl mx-auto text-center px-4">
                                <h2 class="mb-6 text-5xl text-white tracking-5xl">Discover Limitless Possibilities Today!</h2>
                                <p class="mb-12 text-gray-300 max-w-lg mx-auto">Feel free to use this as a starting point and adjust the language to better suit your specific context or messaging.</p>
                                <a class="inline-block px-14 py-4 font-medium tracking-2xl border-2 border-green-400 bg-green-400 hover:bg-green-500 text-black focus:ring-4 focus:ring-green-500 focus:ring-opacity-40 rounded-full transition duration-300"
                                    href="register">Get Started!</a>
                            </div>
                        </div>
                    </div>
                </section>',
            'page_title' => 'Home - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '330599619570398',
            'name' => 'Pricing (Home)',
            'title' => 'Pricing (Home)',
            'slug' => 'pricing-home',
            'body' => '<div class="mb-20 md:max-w-2xl text-center mx-auto"><span class="inline-block mb-4 text-sm text-green-400 font-medium tracking-tighter">Pricing</span>
                  <h2 class="mb-8 text-7xl lg:text-8xl text-white tracking-7xl lg:tracking-8xl">Compare our plans</h2>
                  <p class="mb-12 text-gray-300 max-w-sm mx-auto">Our pricing options are tailored to suit every need, whether you\'re an individual creator or a growing business</p>
                </div>',
            'page_title' => 'Home - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '330599619570398',
            'name' => 'Social Links',
            'title' => 'Social Links',
            'slug' => 'social-links',
            'body' => '<div class="w-full md:w-1/2 lg:flex-1 p-8">
                  <div class="flex flex-wrap -m-2">
                      <div class="w-full p-2"><a class="block py-5 px-8 bg-blueGray-900 bg-opacity-30 rounded-full"
                              href="https://facebook.com">
                              <div class="flex flex-wrap items-center -m-2">
                                  <div class="w-auto p-2"><img title="instagram.svg"
                                          src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTkiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAxOSAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgY2xpcC1wYXRoPSJ1cmwoI2NsaXAwXzIwNl84MjQpIj4KPHBhdGggZD0iTTE4Ljk0MjkgNi4wODMyMkMxOC44OTY2IDUuMDcxOTkgMTguNzM2IDQuMzgxNDEgMTguNTAxMiAzLjc3NzIyQzE4LjI2MjcgMy4xNDMzMiAxNy44ODg3IDIuNTY5MTQgMTcuNDA1NSAyLjA5NDU0QzE2LjkzMSAxLjYxMTI1IDE2LjM1NjcgMS4yMzcxMSAxNS43MjI4IDAuOTk4NTEyQzE1LjExODQgMC43NjM4MjQgMTQuNDI4IDAuNjAzMzU1IDEzLjQxNjggMC41NTc0MDRDMTIuNDAzNyAwLjUxMTAxNyAxMi4wOCAwLjUgOS41IDAuNUM2LjkyMDAzIDAuNSA2LjU5NjM0IDAuNTExMDE3IDUuNTgzMjIgMC41NTcxMTRDNC41NzE5OSAwLjYwMzM1NSAzLjg4MTU1IDAuNzYzOTY5IDMuMjc3MjIgMC45OTg4MDJDMi42NDMzMiAxLjIzNzI2IDIuMDY5MTQgMS42MTEyNSAxLjU5NDU0IDIuMDk0NTRDMS4xMTEyNSAyLjU2ODk5IDAuNzM3MTE0IDMuMTQzMTcgMC40OTg1MTIgMy43NzcwOEMwLjI2MzgyNCA0LjM4MTQxIDAuMTAzMzU1IDUuMDcxOTkgMC4wNTc0MDM2IDYuMDgzMDhDMC4wMTEwMTY4IDcuMDk2MzQgMCA3LjQxOTg4IDAgOS45OTk4NkMwIDEyLjU4IDAuMDExMDE2OCAxMi45MDM3IDAuMDU3NDAzNiAxMy45MTY4QzAuMTAzNSAxNC45Mjc5IDAuMjY0MTE0IDE1LjYxODQgMC40OTg5NDcgMTYuMjIyOEMwLjczNzQwNCAxNi44NTY1IDEuMTExNCAxNy40MzA5IDEuNTk0NjkgMTcuOTA1M0MyLjA2OTE0IDE4LjM4ODYgMi42NDM0NiAxOC43NjI2IDMuMjc3MzcgMTkuMDAxMUMzLjg4MTU1IDE5LjIzNiA0LjU3MjE0IDE5LjM5NjUgNS41ODMzNyAxOS40NDI3QzYuNTk2NjMgMTkuNDg5IDYuOTIwMTcgMTkuNDk5OSA5LjUwMDE0IDE5LjQ5OTlDMTIuMDgwMSAxOS40OTk5IDEyLjQwMzggMTkuNDg5IDEzLjQxNjkgMTkuNDQyN0MxNC40MjgyIDE5LjM5NjUgMTUuMTE4NiAxOS4yMzYgMTUuNzIyOSAxOS4wMDExQzE2Ljk5OSAxOC41MDc2IDE4LjAwNzggMTcuNDk4OCAxOC41MDEyIDE2LjIyMjhDMTguNzM2MiAxNS42MTg0IDE4Ljg5NjYgMTQuOTI3OSAxOC45NDI5IDEzLjkxNjhDMTguOTg5IDEyLjkwMzUgMTkgMTIuNTggMTkgMTBDMTkgNy40MTk4OCAxOC45ODkgNy4wOTYzNCAxOC45NDI5IDYuMDgzMjJaTTE3LjIzMjggMTMuODM4OUMxNy4xOTA2IDE0Ljc2NTIgMTcuMDM1OCAxNS4yNjgyIDE2LjkwNTggMTUuNjAyOUMxNi41ODYyIDE2LjQzMTUgMTUuOTMxNCAxNy4wODYzIDE1LjEwMjggMTcuNDA1OUMxNC43NjgxIDE3LjUzNiAxNC4yNjUxIDE3LjY5MDggMTMuMzM4OCAxNy43MzNDMTIuMzM3MyAxNy43Nzg4IDEyLjAzNjggMTcuNzg4MyA5LjUgMTcuNzg4M0M2Ljk2MzA4IDE3Ljc4ODMgNi42NjI3MyAxNy43Nzg4IDUuNjYxMDYgMTcuNzMzQzQuNzM0OTIgMTcuNjkwOCA0LjIzMTkyIDE3LjUzNiAzLjg5NzA2IDE3LjQwNTlDMy40ODQ1MSAxNy4yNTM2IDMuMTExMjQgMTcuMDEwOCAyLjgwNDggMTYuNjk1MkMyLjQ4OTIzIDE2LjM4ODggMi4yNDY0MiAxNi4wMTU2IDIuMDk0MDcgMTUuNjAyOUMxLjk2NDA0IDE1LjI2ODIgMS44MDkyMyAxNC43NjUyIDEuNzY3MDQgMTMuODM4OUMxLjcyMTM4IDEyLjgzNzMgMS43MTE2NyAxMi41MzY4IDEuNzExNjcgMTAuMDAwMUMxLjcxMTY3IDcuNDYzMzcgMS43MjEzOCA3LjE2MzAyIDEuNzY3MDQgNi4xNjEyMUMxLjgwOTM3IDUuMjM0OTIgMS45NjQwNCA0LjczMTkyIDIuMDk0MDcgNC4zOTcyMUMyLjI0NjQyIDMuOTg0NTEgMi40ODkzNyAzLjYxMTI0IDIuODA0OCAzLjMwNDhDMy4xMTEyNCAyLjk4OTIzIDMuNDg0NTEgMi43NDY0MiAzLjg5NzIxIDIuNTk0MjJDNC4yMzE5MiAyLjQ2NDA0IDQuNzM0OTIgMi4zMDkzNyA1LjY2MTIxIDIuMjY3MDRDNi42NjI4NyAyLjIyMTM4IDYuOTYzMzcgMi4yMTE2NyA5LjUgMi4yMTE2N0g5LjQ5OTg2QzEyLjAzNjUgMi4yMTE2NyAxMi4zMzcgMi4yMjEzOCAxMy4zMzg4IDIuMjY3MTlDMTQuMjY1MSAyLjMwOTM3IDE0Ljc2NzkgMi40NjQxOSAxNS4xMDI4IDIuNTk0MjJDMTUuNTE1MyAyLjc0NjU3IDE1Ljg4ODYgMi45ODkzNyAxNi4xOTUxIDMuMzA0OEMxNi41MTA2IDMuNjExMjQgMTYuNzUzNCAzLjk4NDUxIDE2LjkwNTYgNC4zOTcyMUMxNy4wMzU4IDQuNzMxOTIgMTcuMTkwNiA1LjIzNDkyIDE3LjIzMjggNi4xNjEyMUMxNy4yNzg1IDcuMTYyODcgMTcuMjg4MiA3LjQ2MzM3IDE3LjI4ODIgMTBDMTcuMjg4MiAxMi41MzY4IDE3LjI3ODYgMTIuODM3MSAxNy4yMzI4IDEzLjgzODlaIiBmaWxsPSJ1cmwoI3BhaW50MF9saW5lYXJfMjA2XzgyNCkiLz4KPHBhdGggZD0iTTkuNDk5MzggNS4xMjEwOUM2LjgwNTE4IDUuMTIxMDkgNC42MjEwOSA3LjMwNTMzIDQuNjIxMDkgOS45OTk1M0M0LjYyMTA5IDEyLjY5MzcgNi44MDUxOCAxNC44Nzc4IDkuNDk5MzggMTQuODc3OEMxMi4xOTM3IDE0Ljg3NzggMTQuMzc3OCAxMi42OTM3IDE0LjM3NzggOS45OTk1M0MxNC4zNzc4IDcuMzA1MzMgMTIuMTkzNyA1LjEyMTA5IDkuNDk5MzggNS4xMjEwOVpNOS40OTkzOCAxMy4xNjYxQzcuNzUwNiAxMy4xNjYgNi4zMzI3NiAxMS43NDgzIDYuMzMyOTEgOS45OTkzOEM2LjMzMjkxIDguMjUwNiA3Ljc1MDYgNi44MzI3NiA5LjQ5OTUzIDYuODMyNzZDMTEuMjQ4NSA2LjgzMjkxIDEyLjY2NjEgOC4yNTA2IDEyLjY2NjEgOS45OTkzOEMxMi42NjYxIDExLjc0ODMgMTEuMjQ4MyAxMy4xNjYxIDkuNDk5MzggMTMuMTY2MVoiIGZpbGw9InVybCgjcGFpbnQxX2xpbmVhcl8yMDZfODI0KSIvPgo8cGF0aCBkPSJNMTUuNzExNyA0LjkyOTE2QzE1LjcxMTcgNS41NTg3MiAxNS4yMDEzIDYuMDY5MTEgMTQuNTcxNyA2LjA2OTExQzEzLjk0MiA2LjA2OTExIDEzLjQzMTYgNS41NTg3MiAxMy40MzE2IDQuOTI5MTZDMTMuNDMxNiA0LjI5OTQ2IDEzLjk0MiAzLjc4OTA2IDE0LjU3MTcgMy43ODkwNkMxNS4yMDEzIDMuNzg5MDYgMTUuNzExNyA0LjI5OTQ2IDE1LjcxMTcgNC45MjkxNloiIGZpbGw9InVybCgjcGFpbnQyX2xpbmVhcl8yMDZfODI0KSIvPgo8L2c+CjxkZWZzPgo8bGluZWFyR3JhZGllbnQgaWQ9InBhaW50MF9saW5lYXJfMjA2XzgyNCIgeDE9IjEuNTk0NDUiIHkxPSIxNy45MDU0IiB4Mj0iMTcuNDA1NSIgeTI9IjIuMDk0MjkiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIj4KPHN0b3Agc3RvcC1jb2xvcj0iI0ZGRDYwMCIvPgo8c3RvcCBvZmZzZXQ9IjAuNSIgc3RvcC1jb2xvcj0iI0ZGMDEwMCIvPgo8c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiNEODAwQjkiLz4KPC9saW5lYXJHcmFkaWVudD4KPGxpbmVhckdyYWRpZW50IGlkPSJwYWludDFfbGluZWFyXzIwNl84MjQiIHgxPSI2LjA0OTk1IiB5MT0iMTMuNDQ5IiB4Mj0iMTIuOTQ5IiB5Mj0iNi41NDk5NSIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiPgo8c3RvcCBzdG9wLWNvbG9yPSIjRkY2NDAwIi8+CjxzdG9wIG9mZnNldD0iMC41IiBzdG9wLWNvbG9yPSIjRkYwMTAwIi8+CjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iI0ZEMDA1NiIvPgo8L2xpbmVhckdyYWRpZW50Pgo8bGluZWFyR3JhZGllbnQgaWQ9InBhaW50Ml9saW5lYXJfMjA2XzgyNCIgeDE9IjEzLjc2NTYiIHkxPSI1LjczNTE4IiB4Mj0iMTUuMzc3OCIgeTI9IjQuMTIzMDEiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIj4KPHN0b3Agc3RvcC1jb2xvcj0iI0YzMDA3MiIvPgo8c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiNFNTAwOTciLz4KPC9saW5lYXJHcmFkaWVudD4KPGNsaXBQYXRoIGlkPSJjbGlwMF8yMDZfODI0Ij4KPHJlY3Qgd2lkdGg9IjE5IiBoZWlnaHQ9IjE5IiBmaWxsPSJ3aGl0ZSIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMCAwLjUpIi8+CjwvY2xpcFBhdGg+CjwvZGVmcz4KPC9zdmc+Cg=="
                                          alt="" width="19" height="20"></div>
                                  <div class="flex-1 p-2">
                                      <p class="text-gray-300">Follow us on Facebook</p>
                                  </div>
                              </div>
                          </a>
                      </div>
                      <div class="w-full p-2"><a class="block py-5 px-8 bg-blueGray-900 bg-opacity-30 rounded-full"
                              href="https://twitter.com">
                              <div class="flex flex-wrap items-center -m-2">
                                  <div class="w-auto p-2"><img src="../../themes/modern/assets/images/footers/twitter.svg" alt="">
                                  </div>
                                  <div class="flex-1 p-2">
                                      <p class="text-gray-300">Follow us on Twitter</p>
                                  </div>
                              </div>
                          </a>
                      </div>
                      <div class="w-full p-2"><a class="block py-5 px-8 bg-blueGray-900 bg-opacity-30 rounded-full"
                              href="https://instagram.com">
                              <div class="flex flex-wrap items-center -m-2">
                                  <div class="w-auto p-2"><img src="../../themes/modern/assets/images/footers/instagram.svg" alt="">
                                  </div>
                                  <div class="flex-1 p-2">
                                      <p class="text-gray-300">Follow us on Instagram</p>
                                  </div>
                              </div>
                          </a>
                      </div>
                  </div>
              </div>',
            'page_title' => 'Home - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '330599619570398',
            'name' => 'Tools',
            'title' => 'Tools',
            'slug' => 'tools',
            'body' => '<section class="overflow-hidden">
                    <div class="container px-4 mx-auto">
                        <div class="mb-20 md:max-w-xl text-center mx-auto">
                            <h2 class="text-4xl lg:text-8xl text-white mb-8 tracking-7xl lg:tracking-8xl animated-gradient-text">
                                Dynamic Content Creation
                            </h2>
                            <p class="mb-20 text-gray-300 md:max-w-md mx-auto">
                                Leverage OpenAI\'s dynamic content creation. Our advanced algorithms generate diverse, compelling content, ensuring your messaging stays fresh and engaging.
                            </p>
                        </div>
                    </div>
                </section>',
            'page_title' => 'Tools - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '330599619570398',
            'name' => 'Features',
            'title' => 'Features',
            'slug' => 'features',
            'body' => '<section class="lg:px-12 py-24 bg-blueGray-950">
                  <div class="container px-4 mx-auto">
                      <div class="text-center"><span
                              class="inline-block mb-4 text-sm text-green-400 font-medium tracking-tighter">Features</span>
                          <h2 class="mb-6 text-7xl lg:text-7xl text-white tracking-8xl md:max-w-md mx-auto">AI Content Creator</h2>
                          <p class="mb-20 text-gray-300 md:max-w-md mx-auto">Effortless and Innovative Content Creation with AI. Elevate Your Messaging in Minutes.</p>
                          <div class="flex flex-wrap -m-4">
                              <div class="w-full md:w-1/2 lg:w-1/4 p-4">
                                  <div
                                      class="relative pt-16 px-10 pb-16 h-full bg-gradient-radial-dark border border-gray-800 border-opacity-30 rounded-5xl">
                                      <div class="relative z-10"><img class="mb-8 mx-auto"
                                              src="../../themes/modern/assets/images/cards/card-image1.png" alt="">
                                          <h3 class="mb-6 text-3xl text-white tracking-3xl">AI Powered Creation</h3>
                                          <p class="text-white text-opacity-60">Generate content effortlessly with advanced AI technology</p>
                                      </div>
                                  </div>
                              </div>
                              <div class="w-full md:w-1/2 lg:w-1/4 p-4">
                                  <div
                                      class="relative pt-16 px-10 pb-16 h-full bg-gradient-radial-dark border border-gray-800 border-opacity-30 rounded-5xl">
                                      <div class="relative z-10"><img class="mb-8 mx-auto"
                                              src="../../themes/modern/assets/images/cards/card-image1.png" alt="">
                                          <h3 class="mb-6 text-3xl text-white tracking-3xl">User-Friendly Interface</h3>
                                          <p class="text-white text-opacity-60">Navigate with ease through an intuitive interface for a smooth user experience</p>
                                      </div>
                                  </div>
                              </div>
                              <div class="w-full md:w-1/2 lg:w-1/4 p-4">
                                  <div
                                      class="relative pt-16 px-10 pb-16 h-full bg-gradient-radial-dark border border-gray-800 border-opacity-30 rounded-5xl">
                                      <div class="relative z-10"><img class="mb-8 mx-auto"
                                              src="../../themes/modern/assets/images/cards/card-image1.png" alt="">
                                          <h3 class="mb-6 text-3xl text-white tracking-3xl">Customizable Models</h3>
                                          <p class="text-white text-opacity-60">Tailor the system to your specific needs for optimal performance</p>
                                      </div>
                                  </div>
                              </div>
                              <div class="w-full md:w-1/2 lg:w-1/4 p-4">
                                  <div
                                      class="relative pt-16 px-10 pb-16 h-full bg-gradient-radial-dark border border-gray-800 border-opacity-30 rounded-5xl">
                                      <div class="relative z-10"><img class="mb-8 mx-auto"
                                              src="../../themes/modern/assets/images/cards/card-image1.png" alt="">
                                          <h3 class="mb-6 text-3xl text-white tracking-3xl">24/7 Customer Support</h3>
                                          <p class="text-white text-opacity-60">Receive dedicated support around the clock for a positive user experience</p>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </section>',
            'page_title' => 'Features - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '330599619570398',
            'name' => 'About',
            'title' => 'About',
            'slug' => 'about',
            'body' => '<section class="relative lg:px-12 pt-20 lg:pb-10 overflow-hidden">
                  <div class="container px-4 mx-auto">
                      <div class="relative z-10 py-4 rounded-t-5xl">
                          <div class="px-4 text-center mx-auto max-w-5xl">
                              <span
                                  class="inline-block px-6 py-1 mb-3 font-medium tracking-tighter bg-gray-900 hover:bg-gray-700 text-green-400 focus:ring-4 focus:ring-green-500 focus:ring-opacity-40 rounded-full transition duration-300">About Us</span>
                              <h1 class="mb-4 text-black tracking-3xl animated-gradient-text text-7xl">
                                  AITOOLS
                              </h1>
                              <p class="mb-6 text-gray-100">
                                  Powered by OpenAI
                              </p>
                          </div>
                      </div>
                      <div class="">
                          <div class="max-w-5xl mx-auto">
                              <div class="border-b border-blueGray-900">
                                  <p class="mb-10 text-xl text-gray-100 tracking-2xl">
                                      Welcome to AITOOLS, where innovation meets intelligence. We are a pioneering force in leveraging OpenAI technology to revolutionize the way businesses harness artificial intelligence for their needs.
                                  </p>
                              </div>
                              <div class="pt-8">
                                  <h2 class="mb-4 text-blueGray-50 tracking-3xl lg:text-5xl text-4xl">Our Mission</h2>
                                  <p class="mb-10 text-xl text-gray-100 tracking-2xl">
                                      At AITOOLS, our mission is to empower businesses with cutting-edge AI solutions, driving efficiency, and unlocking new possibilities. We believe in the transformative potential of AI and are committed to delivering state-of-the-art tools that redefine industry standards.
                                  </p>
                                  <h2 class="mb-4 text-blueGray-50 tracking-3xl lg:text-5xl text-4xl">Why AITOOLS?</h2>
                                  <p class="mb-10 text-xl text-gray-100 tracking-2xl">OpenAI-Powered: We harness the power of OpenAI, a leader in artificial intelligence, to bring you the latest advancements in machine learning and natural language processing.</p>
                                  <p class="mb-10 text-xl text-gray-100 tracking-2xl">Innovation at the Core: AITOOLS is driven by a passion for innovation. We constantly push boundaries, exploring new avenues to deliver AI solutions that go beyond expectations.</p>
                                  <p class="mb-10 text-xl text-gray-100 tracking-2xl">User-Centric Approach: Our tools are designed with you in mind. We prioritize user experience, ensuring our AI solutions are intuitive, efficient, and seamlessly integrate into your workflow.</p>
                                  <p class="mb-10 text-xl text-gray-100 tracking-2xl">Commitment to Excellence: AITOOLS is built on a foundation of excellence. We are committed to providing top-notch AI tools, backed by a dedicated team that strives for continuous improvement.</p>
                                  <h2 class="mb-4 text-blueGray-50 tracking-3xl lg:text-5xl text-4xl">Explore the Future with AITOOLS</h2>
                                  <p class="mb-10 text-xl text-gray-100 tracking-2xl">Join us on this journey into the future of artificial intelligence. Whether you are a small startup or a large enterprise, AITOOLS is your trusted partner for intelligent, OpenAI-powered solutions.</p>
                                  <p class="mb-10 text-xl text-gray-100 tracking-2xl">Discover the limitless possibilities of AI with AITOOLS!</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </section>',
            'page_title' => 'About Us - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '330599619570398',
            'name' => 'Pricing',
            'title' => 'Pricing',
            'slug' => 'pricing',
            'body' => '<div class="mb-12 md:max-w-2xl text-center mx-auto">
                    <span class="inline-block mb-4 text-sm text-green-400 font-medium tracking-tighter">OUR PLANS</span>
                    <h2 class="mb-8 text-7xl lg:text-7xl text-white tracking-7xl lg:tracking-8xl animated-gradient-text">Choose a plan for a more advanced business</h2>
                </div>',
            'page_title' => 'Pricing - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '330599619570398',
            'name' => 'Contact Us',
            'title' => 'Contact Us',
            'slug' => 'contact',
            'body' => '<section class="lg:px-12 py-24 overflow-hidden">
                  <div class="container px-4 mx-auto">
                      <div class="mb-20 md:max-w-2xl text-center mx-auto">
                          <span class="inline-block mb-4 text-sm text-green-400 font-medium tracking-tighter">READY TO SUPPORT US</span>
                          <h2 class="mb-8 text-7xl lg:text-8xl text-white tracking-7xl lg:tracking-8xl animated-gradient-text">
                              Get in touch with us
                          </h2>
                          <p class="mb-12 text-gray-300 max-w-lg mx-auto">
                              Let us help you become even greater at what you do. Fill out the following form and we will get back to you in the next 24 hours
                          </p>
                      </div>
                      <div class="max-w-4xl mx-auto">
                          <div class="flex flex-wrap -m-4">
                              <div class="w-full md:w-1/2 p-4">
                                  <div class="flex flex-col h-full p-3 bg-gradient-radial-dark rounded-5xl">
                                      <img class="w-full rounded-3xl" src="../../themes/modern/assets/images/contact/image2.png"
                                          alt="" />
                                      <div class="flex flex-col justify-between p-8 flex-1">
                                          <div class="w-auto">
                                              <p class="mb-3 text-2xl text-white font-medium tracking-2xl">
                                                  New York
                                              </p>
                                              <p class="mb-6 text-gray-300">
                                                  2880 Broadway, New York, NY 10025, USA
                                              </p>
                                              <a class="mb-4 inline-block text-white text-lg font-medium underline"
                                                  href="#">hello@yourdomain.com</a>
                                              <p class="mb-8 text-white text-lg font-medium underline">
                                                  +1 891 4937
                                              </p>
                                          </div>
                                          <div class="w-auto">
                                              <a class="group flex items-center justify-center px-14 py-4 border-2 border-green-400 bg-transparent hover:bg-green-500 focus:ring-4 focus:ring-green-500 focus:ring-opacity-40 rounded-full transition duration-300"
                                                  href="https://maps.app.goo.gl/QxZ2c3QUWJU3UPgb6" target="_blank">
                                                  <div class="mr-2.5 inline-block hover:bg-gray-800">
                                                      <img src="../../themes/modern/assets/images/contact/sent.svg" alt="" />
                                                  </div>
                                                  <span
                                                      class="text-xs text-center text-green-400 group-hover:text-black font-light tracking-2xl">Start GPS</span>
                                              </a>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="w-full md:w-1/2 p-4">
                                  <div class="flex flex-col h-full p-3 bg-gradient-radial-dark">
                                      <img class="rounded-5xl mx-auto md:mr-0"
                                          src="../../themes/modern/assets/images/contact/googlemap.png" alt="" />
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </section>',
            'page_title' => 'Contact Us - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '330599619570398',
            'name' => 'FAQs',
            'title' => 'FAQs',
            'slug' => 'faq',
            'body' => '<section class="relative lg:px-12 py-20 overflow-hidden">
                  <div class="container px-4 mx-auto">
                      <div class="mb-12 md:max-w-xl text-center mx-auto">
                          <span class="inline-block mb-4 text-sm text-green-400 font-medium tracking-tighter">FREQUENTLY ASK QUESTION</span>
                          <h2 class="text-7xl lg:text-8xl text-white tracking-7xl lg:tracking-8xl">You ask? We answer</h2>
                      </div>
                      <div class="flex flex-wrap -m-4 mb-16">
                          <div class="w-full md:w-1/2 lg:w-1/3 p-4">
                              <div
                                  class="px-10 pt-14 pb-10 h-full bg-gradient-radial-dark border border-gray-900 border-opacity-30 rounded-3xl">
                                  <div class="flex flex-col justify-between h-full">
                                      <div class="w-auto">
                                          <h3 class="mb-6 text-2xl text-white tracking-2xl">What is AI Tools Content Creator, and how does it work?</h3>
                                          <p class="mb-16 text-white text-opacity-60">AI Tools Content Creator is an online platform equipped with advanced artificial intelligence technology. It leverages AI-powered tools and features to assist users in generating compelling content effortlessly. The platform analyzes user input, understands context, and provides suggestions to enhance the quality of blog posts, articles, social media posts, and more.</p>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="w-full md:w-1/2 lg:w-1/3 p-4">
                              <div
                                  class="px-10 pt-14 pb-10 h-full bg-gradient-radial-dark border border-gray-900 border-opacity-30 rounded-3xl">
                                  <div class="flex flex-col justify-between h-full">
                                      <div class="w-auto">
                                          <h3 class="mb-6 text-2xl text-white tracking-2xl">How can AI Tools Content Creator benefit my content creation process?</h3>
                                          <p class="mb-16 text-white text-opacity-60">AI Tools Content Creator streamlines content creation by harnessing the power of artificial intelligence. It accelerates the writing process, suggests improvements, and ensures the creation of high-quality content within minutes. Whether you\'re a blogger, marketer, or social media enthusiast, this platform enhances your efficiency and helps you produce engaging content effortlessly.</p>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="w-full md:w-1/2 lg:w-1/3 p-4">
                              <div
                                  class="px-10 pt-14 pb-10 h-full bg-gradient-radial-dark border border-gray-900 border-opacity-30 rounded-3xl">
                                  <div class="flex flex-col justify-between h-full">
                                      <div class="w-auto">
                                          <h3 class="mb-6 text-2xl text-white tracking-2xl">What types of content can I create using AI Tools Content Creator?</h3>
                                          <p class="mb-16 text-white text-opacity-60">AI Tools Content Creator is versatile and supports the creation of various content types. You can use it to generate engaging blog posts, articles, social media posts, and more. The platform\'s AI capabilities adapt to different writing styles and topics, making it a valuable tool for a wide range of content creators.</p>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="w-full md:w-1/2 lg:w-1/3 p-4">
                              <div
                                  class="px-10 pt-14 pb-10 h-full bg-gradient-radial-dark border border-gray-900 border-opacity-30 rounded-3xl">
                                  <div class="flex flex-col justify-between h-full">
                                      <div class="w-auto">
                                          <h3 class="mb-6 text-2xl text-white tracking-2xl">How user-friendly is AI Tools Content Creator for beginners?</h3>
                                          <p class="mb-16 text-white text-opacity-60">AI Tools Content Creator is designed with user-friendliness in mind. Its intuitive interface and straightforward features make it accessible for both beginners and experienced content creators. The platform provides guidance throughout the content creation process, ensuring that even those new to AI-powered tools can easily navigate and produce compelling content.</p>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="w-full md:w-1/2 lg:w-1/3 p-4">
                              <div
                                  class="px-10 pt-14 pb-10 h-full bg-gradient-radial-dark border border-gray-900 border-opacity-30 rounded-3xl">
                                  <div class="flex flex-col justify-between h-full">
                                      <div class="w-auto">
                                          <h3 class="mb-6 text-2xl text-white tracking-2xl">Can I customize the AI-generated content to fit my unique style and preferences?</h3>
                                          <p class="mb-16 text-white text-opacity-60">Absolutely! AI Tools Content Creator allows users to customize and fine-tune the AI-generated content according to their unique style and preferences. You have the flexibility to make edits, add personal touches, and ensure that the final output aligns perfectly with your individual writing style and requirements.</p>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="w-full md:w-1/2 lg:w-1/3 p-4">
                              <div
                                  class="px-10 pt-14 pb-10 h-full bg-gradient-radial-dark border border-gray-900 border-opacity-30 rounded-3xl">
                                  <div class="flex flex-col justify-between h-full">
                                      <div class="w-auto">
                                          <h3 class="mb-6 text-2xl text-white tracking-2xl">How often are updates and new features released for AI Tools Content Creator?</h3>
                                          <p class="mb-16 text-white text-opacity-60">AI Tools Content Creator is typically updated regularly to introduce new features, improvements, and enhancements. Keeping the platform up-to-date ensures that users have access to the latest advancements in AI technology, making their content creation experience even more efficient and effective.</p>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <p class="text-lg text-center">For any additional inquiries, please don\'t hesitate to <a class="text-green-400" href="contact">contact our support team</a> for more detailed information.</p>
                  </div>
              </section>',
            'page_title' => 'FAQs - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '330599619570398',
            'name' => 'Privacy Policy',
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'body' => '<section class="relative lg:px-12 pt-20 overflow-hidden">
                  <div class="container px-4 mx-auto">
                      <div class="pt-6 pb-6 border-b border-blueGray-900">
                          <div class="flex flex-wrap lg:items-center -m-8">
                              <div class="w-full px-8 lg:px-16">
                                  <div class="md:max-w-full">
                                      <h2 class="mb-4 text-5xl lg:text-7xl text-white tracking-5xl lg:tracking-7xl">
                                          Privacy Policy
                                      </h2>
                                      <p class="mb-6 text-white text-opacity-60">
                                          Last Updated : 17-02-2024
                                      </p>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="flex flex-wrap mt-6 md:divide-x md:divide-gray-900 border-b border-gray-900 -m-8">
                          <div class="w-full md:flex-1 md:pl-16 p-8">
                              <div>
                                  <div class="pt-1">
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">
                                          Thank you for choosing AITools and trusting us with your personal information. This Privacy Policy outlines how we collect, use, share, and protect your information when you use our services.
                                      </p>
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">1. Information We Collect:</h3>
              
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">We may collect personal information such as your name, email address, and other relevant details when you voluntarily provide it to us while using our website or services.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">2. How We Use Your Information:</h3>
              
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">We use the information collected to provide and improve our services, personalize your experience, communicate with you, and send updates about our products and services. We do not sell or share your personal information with third parties for marketing purposes.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">3. Information Security:</h3>
              
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">We take the security of your personal information seriously. We implement reasonable security measures to protect against unauthorized access, disclosure, alteration, or destruction of your information.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">4. Cookies:</h3>
              
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">We may use cookies to enhance your user experience. You can choose to disable cookies through your browser settings, but please note that some features of our website may not function properly as a result.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">5. Third-Party Links:</h3>
              
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">Our website may contain links to third-party websites. Please be aware that we are not responsible for the privacy practices of these external sites. We encourage you to read the privacy policies of these websites when you leave our site.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">6. Children\'s Privacy:</h3>
              
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">Our services are not intended for individuals under the age of 13. We do not knowingly collect personal information from children. If you are a parent or guardian and believe your child has provided us with personal information, please contact us, and we will take steps to remove such information.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">7. Changes to This Privacy Policy:</h3>
              
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new policy on this page. We recommend checking this page periodically to stay informed about how we are protecting and using your information.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">8. Contact Us:</h3>
              
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">If you have any questions or concerns about our Privacy Policy, please contact us at <a class="text-green-400" href="mailto:hello@yourdomain.com">hello@yourdomain.com</a>.</p>
              
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">By using our website or services, you agree to the terms outlined in this Privacy Policy.</p>
              
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">Thank you for choosing AITools.</p>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </section>',
            'page_title' => 'Privacy Policy - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '330599619570398',
            'name' => 'Refund Policy',
            'title' => 'Refund Policy',
            'slug' => 'refund-policy',
            'body' => '<section class="relative lg:px-12 pt-20 overflow-hidden">
                  <div class="container px-4 mx-auto">
                      <div class="pt-6 pb-6 border-b border-blueGray-900">
                          <div class="flex flex-wrap lg:items-center -m-8">
                              <div class="w-full px-8 lg:px-16">
                                  <div class="md:max-w-full">
                                      <h2 class="mb-4 text-5xl lg:text-7xl text-white tracking-5xl lg:tracking-7xl">
                                          Refund Policy
                                      </h2>
                                      <p class="mb-6 text-white text-opacity-60">
                                          Last Updated : 17-02-2024
                                      </p>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="flex flex-wrap mt-6 md:divide-x md:divide-gray-900 border-b border-gray-900 -m-8">
                          <div class="w-full md:flex-1 md:pl-16 p-8">
                              <div>
                                  <div class="pt-1">
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">
                                          Thank you for choosing AITools. We value your satisfaction and aim to provide a positive experience with our products. Please review our refund policy below.
                                      </p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">1. Refund Eligibility:</h3>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">We offer refunds under the following circumstances:</p>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">Technical Issues: If you encounter technical issues or errors that significantly hinder your ability to use AITools, and our support team is unable to resolve the problem within a reasonable timeframe.</p>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">Unsatisfactory Service: If you are dissatisfied with the service provided by AITools and have communicated your concerns to our support team without a satisfactory resolution.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">2. Refund Request Process:</h3>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">To request a refund, please follow these steps:</p>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">Contact our customer support team at <a class="text-green-400" href="mailto:hello@yourdomain.com">hello@yourdomain.com</a> within 7 days of the purchase date.</p>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">Provide a detailed explanation of the reason for your refund request.</p>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">Allow our support team a reasonable timeframe to address and resolve the issue. If a resolution cannot be reached, we will proceed with the refund process.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">3. Refund Amount:</h3>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">Refunds, if approved, will be issued for the amount paid during the original purchase. Any additional charges or fees incurred during the transaction may not be refundable.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">4. Refund Method:</h3>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">Refunds will typically be processed using the same method as the original payment. If this is not possible, alternative arrangements will be made in consultation with the user.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">5. Exceptions:</h3>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">Refunds will not be granted if the user violates our terms of service.</p>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">Refunds are not applicable for subscription services beyond the initial trial period.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">6. Contact Us:</h3>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">If you have any questions or concerns about our refund policy, please contact us at <a class="text-green-400" href="mailto:hello@yourdomain.com">hello@yourdomain.com</a>.</p>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">By using our services, you agree to the terms outlined in this Refund Policy.</p>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">Thank you for choosing AITools.</p>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </section>',
            'page_title' => 'Refund Policy - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '330599619570398',
            'name' => 'Terms and Conditions',
            'title' => 'Terms and Conditions',
            'slug' => 'terms-and-conditions',
            'body' => '<section class="relative lg:px-12 pt-20 overflow-hidden">
                  <div class="container px-4 mx-auto">
                      <div class="pt-6 pb-6 border-b border-blueGray-900">
                          <div class="flex flex-wrap lg:items-center -m-8">
                              <div class="w-full px-8 lg:px-16">
                                  <div class="md:max-w-full">
                                      <h2 class="mb-4 text-5xl lg:text-7xl text-white tracking-5xl lg:tracking-7xl">
                                          Terms and Conditions
                                      </h2>
                                      <p class="mb-6 text-white text-opacity-60">
                                          Last Updated : 17-02-2024
                                      </p>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="flex flex-wrap mt-6 md:divide-x md:divide-gray-900 border-b border-gray-900 -m-8">
                          <div class="w-full md:flex-1 md:pl-16 p-8">
                              <div>
                                  <div class="pt-1">
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">
                                          Thank you for choosing AITools. Please read these Terms and Conditions carefully before using our services.
                                      </p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">1. Acceptance of Terms:</h3>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">By accessing or using AITools, you agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, please do not use our services.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">2. Use of Services:</h3>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">You may use our services for personal or business purposes, as intended by AITools. You agree not to engage in any activity that interferes with or disrupts the functionality of our platform.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">3. Intellectual Property:</h3>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">The content, features, and functionality of AITools are the property of AITools and are protected by copyright, trademark, and other intellectual property laws. You may not reproduce, distribute, modify, or create derivative works without our express consent.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">4. User Accounts:</h3>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">To access certain features, you may be required to create an account. You are responsible for maintaining the confidentiality of your account information and agree to notify us immediately of any unauthorized use.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">5. Privacy Policy:</h3>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">Your use of AITools is also governed by our <a class="text-green-400" href="/privacy-policy" target="_blank">Privacy Policy</a>. By using our services, you consent to the terms outlined in the Privacy Policy.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">6. Refund Policy:</h3>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">Our Refund Policy outlines the conditions under which refunds may be granted. Please review our Refund Policy for more details.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">7. Support:</h3>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">For any inquiries, concerns, or support-related issues, please contact our customer support team at <a class="text-green-400" href="mailto:hello@yourdomain.com">hello@yourdomain.com</a>.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">8. Changes to Terms:</h3>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">We reserve the right to update or modify these Terms and Conditions at any time. The date of the latest update will be displayed at the beginning of this document. It is your responsibility to review these terms periodically.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">9. Termination:</h3>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">We reserve the right to terminate or suspend your access to AITools at our discretion, without prior notice, for any violation of these Terms and Conditions.</p>
              
                                      <h3 class="text-4xl mb-4 text-gray-100 tracking-2xl">10. Governing Law:</h3>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">These Terms and Conditions are governed by and construed in accordance with the laws of India.</p>
                                      <p class="mb-9 text-xl text-gray-100 tracking-2xl">Thank you for choosing AITools.</p>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </section>',
            'page_title' => 'Terms and Conditions - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);


        // Modern Theme Content (Orange)
        DB::table('pages')->insert([
            'theme_id' => '317109101703740',
            'name' => 'Hero',
            'title' => 'Hero',
            'slug' => 'hero',
            'body' => '<div class="relative container px-4 mx-auto"> 
                            <div class="max-w-7xl mx-auto">
                                <div class="max-w-4xl mx-auto text-center">
                                    <h1
                                        class="font-heading text-5xl xs:text-6xl md:text-8xl xl:text-10xl font-bold text-gray-900 mb-8 sm:mb-14">
                                        <span class="animated-gradient-text">AI Content Magic</span>
                                        <span class="font-serif italic">AI Tools</span>
                                    </h1>
                                </div>
                                <div class="md:flex mb-14 max-w-xs sm:max-w-sm md:max-w-none">
                                    <div class="mb-6 md:mb-0 md:mr-8 pt-3">
                                        <svg width="84" height="10" viewbox="0 0 84 10" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M1 4.25C0.585786 4.25 0.25 4.58579 0.25 5C0.25 5.41421 0.585786 5.75 1 5.75L1 4.25ZM84 5.00001L76.5 0.669879L76.5 9.33013L84 5.00001ZM1 5.75L77.25 5.75001L77.25 4.25001L1 4.25L1 5.75Z"
                                                fill="#1E254C"></path>
                                        </svg>
                                    </div>
                                    <div class="max-w-md">
                                        <p class="md:text-xl text-gray-900 font-semibold">
                                            Effortless content creation with AI Tools Content Creator. Say goodbye to time-consuming writing tasks. Boost your productivity and creativity instantly.
                                        </p>
                                    </div>
                                </div>
                                <a class="relative group inline-block w-full sm:w-auto py-4 px-6 mb-24 text-white font-semibold rounded-md bg-orange-900 overflow-hidden"
                                    href="register">
                                    <div
                                        class="absolute top-0 right-full w-full h-full bg-gray-900 transform group-hover:translate-x-full group-hover:scale-102 transition duration-500">
                                    </div>
                                    <div class="relative flex items-center justify-center">
                                        <span class="mr-4">Get Started Now</span>
                                        <span>
                                            <svg width="8" height="12" viewbox="0 0 8 12" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6.83 5.29L2.59 1.05C2.49704 0.956274 2.38644 0.881879 2.26458 0.83111C2.14272 0.780342 2.01202 0.754204 1.88 0.754204C1.74799 0.754204 1.61729 0.780342 1.49543 0.83111C1.37357 0.881879 1.26297 0.956274 1.17 1.05C0.983753 1.23736 0.879211 1.49082 0.879211 1.755C0.879211 2.01919 0.983753 2.27264 1.17 2.46L4.71 6L1.17 9.54C0.983753 9.72736 0.879211 9.98082 0.879211 10.245C0.879211 10.5092 0.983753 10.7626 1.17 10.95C1.26344 11.0427 1.37426 11.116 1.4961 11.1658C1.61794 11.2155 1.7484 11.2408 1.88 11.24C2.01161 11.2408 2.14207 11.2155 2.26391 11.1658C2.38575 11.116 2.49656 11.0427 2.59 10.95L6.83 6.71C6.92373 6.61704 6.99813 6.50644 7.04889 6.38458C7.09966 6.26272 7.1258 6.13201 7.1258 6C7.1258 5.86799 7.09966 5.73728 7.04889 5.61543C6.99813 5.49357 6.92373 5.38297 6.83 5.29Z"
                                                    fill="#FFF2EE"></path>
                                            </svg></span>
                                    </div>
                                </a>
                                <div class="lg:flex">
                                    <div class="w-fuk mb-20 lg:mb-0 lg:mr-32">
                                        <span class="block mb-6 text-sm font-semibold text-gray-500">TRUSTED BY BIG COMPANIES</span>
                                        <div class="flex items-center -mx-4">
                                            <div class="w-1/3 px-4">
                                                <img class="block"
                                                    src="../../themes/modern-orange/assets/logos/brands/logo-example3.png"
                                                    alt="" />
                                            </div>
                                            <div class="w-1/3 px-4">
                                                <img class="block"
                                                    src="../../themes/modern-orange/assets/logos/brands/logo-example2.png"
                                                    alt="" />
                                            </div>
                                            <div class="w-1/3 px-4">
                                                <img class="block"
                                                    src="../../themes/modern-orange/assets/logos/brands/logo-example1.png"
                                                    alt="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="relative lg:-mt-20 w-full max-w-md">
                                        <img class="absolute top-0 left-0 w-full transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-110"
                                            src="../../themes/modern-orange/assets/images/headers/bg-folder.svg"
                                            alt="" />
                                        <div class="relative flex flex-col">
                                            <div class="xs:w-100 max-w-xs pl-5 xs:pl-10 pt-10 pr-20 xs:pr-5 z-10">
                                                <h3
                                                    class="text-xl font-semibold text-gray-900 transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                                                    We are building the future together
                                                </h3>
                                            </div>
                                            <div class="xs:w-100 max-w-md -mt-10 px-5 xs:px-10 pb-12 pt-18 bg-orange-50 rounded-b-3xl">
                                                <div class="flex items-center">
                                                    <div
                                                        class="transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                                                        <span class="text-3xl xs:text-5xl font-semibold text-gray-900">33+</span>
                                                        <span class="block text-sm text-gray-500">Content Generators</span>
                                                    </div>
                                                    <div class="h-12 w-px mx-auto bg-orange-200"></div>
                                                    <div
                                                        class="transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                                                        <span class="text-3xl xs:text-5xl font-semibold text-gray-900">50+</span>
                                                        <span class="block text-sm text-gray-500">Supported Languages</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>',
            'page_title' => 'Home - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '317109101703740',
            'name' => 'Tools',
            'title' => 'Tools',
            'slug' => 'tools',
            'body' => '<div class="flex flex-wrap items-center -mx-4 mb-24">
                            <div class="w-full lg:w-2/3 px-4 mb-16 lg:mb-0">
                            <div class="max-w-lg lg:max-w-2xl mx-auto lg:mx-0">
                                <h1 class="font-heading text-5xl xs:text-6xl md:text-7xl font-bold text-gray-900 mb-8">
                                <span class="animated-gradient-text">Dynamic</span>
                                <span class="font-serif italic">Content Creation</span>
                                </h1>
                                <p class="max-w-xl text-2xl md:text-3xl font-semibold text-gray-400">Leverage OpenAI\'s dynamic content creation. Our advanced algorithms generate diverse, compelling content, ensuring your messaging stays fresh and engaging.</p>
                            </div>
                            </div>
                            <div class="w-full lg:w-1/3 px-4">
                            <img class="w-full max-w-lg mx-auto lg:mr-0" src="../../themes/modern-orange/assets/images/features/violet-image.png" alt="">
                            </div>
                        </div>',
            'page_title' => 'Tools - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '317109101703740',
            'name' => 'Features',
            'title' => 'Features',
            'slug' => 'features',
            'body' => '<section class="relative py-20 overflow-hidden">
                        <img class="absolute top-0 right-0" src="../../themes/modern-orange/assets/images/features/star-element-right.png" alt="">
                        <div class="relative container px-4 mx-auto">
                            <div class="max-w-7xl mx-auto">
                                <div class="flex flex-wrap -mx-4 mb-18 items-center">
                                    <div class="w-full lg:w-2/3 xl:w-1/2 px-4 mb-12 lg:mb-0">
                                        <div>
                                            <span
                                                class="inline-block py-1 px-3 mb-4 text-xs font-semibold text-orange-900 bg-orange-50 rounded-full">FEATURES</span>
                                            <h1 class="font-heading text-5xl xs:text-6xl md:text-7xl font-bold text-gray-900">
                                                <span class="animated-gradient-text">AI Content</span>
                                                <span class="font-serif italic">Creator</span>
                                            </h1>
                                        </div>
                                    </div>
                                    <div class="w-full lg:w-1/3 xl:w-1/2 px-4">
                                        <div class="max-w-sm lg:ml-auto">
                                            <p class="text-lg text-gray-500">Effortless and Innovative Content Creation with AI. Elevate Your Messaging in Minutes.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap -mx-4">
                                    <div class="w-full xl:w-1/2 px-4 mb-8 xl:mb-0">
                                        <div class="relative h-100 md:h-125">
                                            <img class="block h-full w-full rounded-3xl object-cover"
                                                src="../../themes/modern-orange/assets/images/features/blue-photo.png" alt="">
                                            <div class="absolute top-0 left-0 h-full w-full p-8 md:p-12">
                                                <div class="flex flex-col items-start justify-between h-full max-w-sm">
                                                    <div>
                                                        <span class="block mb-3 text-sm text-yellow-800 font-semibold uppercase">AI-Powered Creation</span>
                                                        <h3 class="text-3xl md:text-4xl text-white font-semibold">Generate content effortlessly with advanced AI technology</h3>
                                                    </div>
                                                    <a class="relative group inline-block w-full xs:w-auto py-4 px-6 text-orange-900 hover:text-white font-semibold bg-orange-50 rounded-md overflow-hidden transition duration-500"
                                                        href="features">
                                                        <div
                                                            class="absolute top-0 right-full w-full h-full bg-gray-900 transform group-hover:translate-x-full group-hover:scale-105 transition duration-500">
                                                        </div>
                                                        <div class="relative flex items-center justify-center">
                                                            <span class="mr-4">Get started</span>
                                                            <span>
                                                                <svg width="13" height="13" viewbox="0 0 13 13" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M12.92 0.62C12.8185 0.375651 12.6243 0.181475 12.38 0.0799999C12.2598 0.028759 12.1307 0.00157999 12 0H2C1.73478 0 1.48043 0.105357 1.29289 0.292893C1.10536 0.48043 1 0.734784 1 1C1 1.26522 1.10536 1.51957 1.29289 1.70711C1.48043 1.89464 1.73478 2 2 2H9.59L1.29 10.29C1.19627 10.383 1.12188 10.4936 1.07111 10.6154C1.02034 10.7373 0.994202 10.868 0.994202 11C0.994202 11.132 1.02034 11.2627 1.07111 11.3846C1.12188 11.5064 1.19627 11.617 1.29 11.71C1.38296 11.8037 1.49356 11.8781 1.61542 11.9289C1.73728 11.9797 1.86799 12.0058 2 12.0058C2.13201 12.0058 2.26272 11.9797 2.38458 11.9289C2.50644 11.8781 2.61704 11.8037 2.71 11.71L11 3.41V11C11 11.2652 11.1054 11.5196 11.2929 11.7071C11.4804 11.8946 11.7348 12 12 12C12.2652 12 12.5196 11.8946 12.7071 11.7071C12.8946 11.5196 13 11.2652 13 11V1C12.9984 0.869323 12.9712 0.740222 12.92 0.62Z"
                                                                        fill="currentColor"></path>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full xl:w-1/2 px-4">
                                        <div class="flex flex-wrap h-full -mx-4">
                                            <div class="w-full md:w-1/2 px-4 mb-8 md:mb-0">
                                                <div class="flex flex-col h-full">
                                                    <a class="relative block h-full mb-7 pt-8 px-8 pb-5 rounded-3xl bg-green-50 hover:bg-green-100 transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-110"
                                                        href="register">
                                                        <div class="flex flex-col h-full justify-between max-w-sm pr-16">
                                                            <p class="text-sm text-gray-900 mb-10 md:mb-6">Navigate with ease through an intuitive interface for a smooth user experience</p>
                                                            <span class="text-3xl font-semibold text-gray-900">User-Friendly Interface</span>
                                                        </div>
                                                        <img class="absolute bottom-0 right-0 m-5"
                                                            src="../../themes/modern-orange/assets/images/features/arrow.svg" alt="">
                                                    </a>
                                                    <a class="relative block h-full pt-8 px-8 pb-5 rounded-3xl bg-red-50 hover:bg-red-100 transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-110"
                                                        href="register">
                                                        <div class="flex flex-col h-full justify-between max-w-sm pr-16">
                                                            <p class="text-sm text-gray-900 mb-10 md:mb-6">Tailor the system to your specific needs for optimal performance</p>
                                                            <span class="text-3xl font-semibold text-gray-900">Customizable Models</span>
                                                        </div>
                                                        <img class="absolute bottom-0 right-0 m-5"
                                                            src="../../themes/modern-orange/assets/images/features/arrow.svg" alt="">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="w-full md:w-1/2 px-4">
                                                <a class="relative block h-full pt-8 px-8 pb-5 rounded-3xl bg-orange-50 hover:bg-orange-100 transition duration-500 ease-in-out transform hover:-translate-y-1 hover:scale-110"
                                                    href="register">
                                                    <div class="flex h-full flex-col items-start justify-between max-w-sm pr-16">
                                                        <p class="text-sm text-gray-900 mb-10 md:mb-6">Receive dedicated support around the clock for a positive user experience</p>
                                                        <span class="text-3xl font-semibold text-gray-900">24/7 Customer Support</span>
                                                    </div>
                                                    <img class="absolute bottom-0 right-0 m-5"
                                                        src="../../themes/modern-orange/assets/images/features/arrow.svg" alt="">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>',
            'page_title' => 'Features - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '317109101703740',
            'name' => 'About',
            'title' => 'About',
            'slug' => 'about',
            'body' => '<section class="relative py-4 lg:py-4 overflow-hidden"><img class="absolute top-0 left-0 mt-10"
                        src="themes/modern-orange/assets/images/content/stars-left-top.svg" alt=""><img
                        class="absolute bottom-0 right-0" src="themes/modern-orange/assets/images/content/orange-light-right.png"
                        alt="">
                    <div class="relative container px-4 mx-auto">
                        <div class="text-center mb-18">
                            <span
                                class="inline-block py-1 px-3 mb-4 text-xs font-semibold text-orange-900 bg-orange-50 rounded-full">ABOUT US</span>
                            <h1 class="font-heading text-5xl xs:text-6xl md:text-7xl font-bold text-gray-900 mb-12">
                                <span class="animated-gradient-text">AITOOLS</span>
                                <span class="block font-serif italic">Powered by OpenAI</span>
                            </h1>
                        </div>
                        <div class="max-w-lg lg:max-w-3xl xl:max-w-5xl mx-auto">
                            <div class="flex flex-wrap -mx-4">
                                <div class="w-full px-4 lg:mb-0">
                                    <div class="w-full py-4">
                                        <p class="text-xl text-gray-800 mb-12">Welcome to AITOOLS, where innovation meets intelligence. We are a pioneering force in leveraging OpenAI technology to revolutionize the way businesses harness artificial intelligence for their needs.
                                        </p>
                                        <h4 class="text-2xl text-gray-900 font-semibold mb-3">Our Mission</h4>
                                        <p class="text-xl text-gray-800 mb-12">At AITOOLS, our mission is to empower businesses with cutting-edge AI solutions, driving efficiency, and unlocking new possibilities. We believe in the transformative potential of AI and are committed to delivering state-of-the-art tools that redefine industry standards.</p>
                                        <h4 class="text-2xl text-gray-900 font-semibold mb-3">Why AITOOLS?</h4>
                                        <p class="text-xl text-gray-800 mb-12">OpenAI-Powered: We harness the power of OpenAI, a leader in artificial intelligence, to bring you the latest advancements in machine learning and natural language processing.
                                        </p>
                                        <p class="text-xl text-gray-800 mb-12">Innovation at the Core: AITOOLS is driven by a passion for innovation. We constantly push boundaries, exploring new avenues to deliver AI solutions that go beyond expectations.</p>
                                        <p class="text-xl text-gray-800 mb-12">User-Centric Approach: Our tools are designed with you in mind. We prioritize user experience, ensuring our AI solutions are intuitive, efficient, and seamlessly integrate into your workflow.</p>
                                        <p class="text-xl text-gray-800 mb-12">Commitment to Excellence: AITOOLS is built on a foundation of excellence. We are committed to providing top-notch AI tools, backed by a dedicated team that strives for continuous improvement.</p>
                                        <h4 class="text-2xl text-gray-900 font-semibold mb-3">Explore the Future with AITOOLS</h4>
                                        <p class="text-xl text-gray-800 mb-12">Join us on this journey into the future of artificial intelligence. Whether you are a small startup or a large enterprise, AITOOLS is your trusted partner for intelligent, OpenAI-powered solutions.</p>
                                        <p class="text-xl text-gray-800 mb-12">Discover the limitless possibilities of AI with AITOOLS!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>',
            'page_title' => 'About Us - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '317109101703740',
            'name' => 'Contact Us',
            'title' => 'Contact Us',
            'slug' => 'contact',
            'body' => '<section class="relative py-20 md:py-32 overflow-hidden">
                        <img class="absolute top-0 left-0" src="themes/modern-orange/assets/images/contact/light-left-blue.png" alt="">
                        <img class="absolute bottom-0 right-0 -mb-20"
                            src="themes/modern-orange/assets/images/contact/light-orange-right.png" alt="">
                        <div class="relative container px-4 mx-auto">
                            <div class="max-w-7xl mx-auto">
                                <div class="max-w-2xl text-center mx-auto mb-20">
                                    <span
                                        class="inline-block py-1 px-3 mb-4 text-xs font-semibold text-orange-900 bg-orange-50 rounded-full">READY TO SUPPORT US</span>
                                    <h1 class="font-heading text-5xl xs:text-6xl font-bold text-gray-900 mb-4">
                                        <span class="animated-gradient-text">Let’s stay</span>
                                        <span class="font-serif italic">connected</span>
                                    </h1>
                                    <p class="text-xl text-gray-500 font-semibold">We help people to grow their business using saturn ui library with professional and powerfull solution.</p>
                                </div>
                                <div class="xs:max-w-sm lg:max-w-none mx-auto">
                                    <div class="flex flex-wrap items-center -mx-4 mb-18">
                                        <div class="w-full lg:w-1/3 px-4 mb-12 lg:mb-0">
                                            <div class="flex items-center lg:justify-center">
                                                <div
                                                    class="flex flex-shrink-0 mr-5 sm:mr-8 items-center justify-center p-1 w-16 sm:w-20 h-16 sm:h-20 rounded-full bg-blue-200">
                                                    <img src="themes/modern-orange/assets/images/contact/icon-phone.svg" alt="">
                                                </div>
                                                <div>
                                                    <span class="text-lg text-gray-500">Phone</span>
                                                    <span class="block text-lg font-semibold text-gray-900">+1 891 4937</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-full lg:w-1/3 px-4 mb-12 lg:mb-0">
                                            <div class="flex items-center lg:justify-center">
                                                <div
                                                    class="flex flex-shrink-0 mr-5 sm:mr-8 items-center justify-center p-1 w-16 sm:w-20 h-16 sm:h-20 rounded-full bg-yellow-200">
                                                    <img src="themes/modern-orange/assets/images/contact/icon-email.svg" alt="">
                                                </div>
                                                <div>
                                                    <span class="text-lg text-gray-500">Email</span>
                                                    <span class="block text-lg font-semibold text-gray-900">hello@yourdomain.com</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-full lg:w-1/3 px-4">
                                            <div class="flex items-center lg:justify-center">
                                                <div
                                                    class="flex flex-shrink-0 mr-5 sm:mr-8 items-center justify-center p-1 w-16 sm:w-20 h-16 sm:h-20 rounded-full bg-green-200">
                                                    <img class="h-8" src="themes/modern-orange/assets/images/contact/icon-location.svg"
                                                        alt="">
                                                </div>
                                                <div>
                                                    <span class="text-lg text-gray-500">Office</span>
                                                    <span class="block text-lg font-semibold text-gray-900">213, New York</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-4xl overflow-hidden">
                                    <iframe class="w-full" id="gmap_canvas" style="height: 601px;"
                                        src="https://maps.google.com/maps?q=2880%20Broadway,%20New%20York&amp;t=&amp;z=19&amp;ie=UTF8&amp;iwloc=&amp;output=embed"
                                        frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                                </div>
                            </div>
                        </div>
                    </section>',
            'page_title' => 'Contact Us - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '317109101703740',
            'name' => 'FAQs',
            'title' => 'FAQs',
            'slug' => 'faq',
            'body' => '<section class="relative py-20 md:py-32 overflow-hidden bg-gray-50">
    <img class="absolute top-0 left-0 mt-44" src="themes/modern-orange/assets/images/faq/light-blue-left.png" alt="">
    <img class="absolute top-0 right-0 mt-10" src="themes/modern-orange/assets/images/faq/star-right.svg" alt="">
    <div class="relative container px-4 mx-auto">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-24">
                <span
                    class="inline-block py-1 px-3 mb-4 text-xs font-semibold text-orange-900 bg-orange-50 rounded-full">FREQUENTLY ASK QUESTION</span>
                <h1 class="font-heading text-5xl xs:text-6xl md:text-7xl font-bold">
                    <span>You ask? We</span>
                    <span class="font-serif italic">answer</span>
                </h1>
            </div>
            <div class="flex flex-wrap -mx-4 -mb-8">
                <div class="w-full sm:w-1/2 lg:w-1/3 px-4 mb-8">
                    <div
                        class="flex flex-col h-full max-w-xs mx-auto md:max-w-none px-6 py-9 md:pl-9 md:pr-12 bg-white rounded-3xl shadow-lg">
                        <h4 class="text-xl font-semibold text-gray-900 mb-4">What is Writegemini Content Creator, and how does it work?</h4>
                        <p class="text-gray-500 mb-4">Writegemini Content Creator is an online platform equipped with advanced artificial intelligence technology. It leverages AI-powered tools and features to assist users in generating compelling content effortlessly. The platform analyzes user input, understands context, and provides suggestions to enhance the quality of blog posts, articles, social media posts, and more.</p>
                    </div>
                </div>
                <div class="w-full sm:w-1/2 lg:w-1/3 px-4 mb-8">
                    <div
                        class="flex flex-col h-full max-w-xs mx-auto md:max-w-none px-6 py-9 md:pl-9 md:pr-12 bg-white rounded-3xl shadow-lg">
                        <h4 class="text-xl font-semibold text-gray-900 mb-4">How can Writegemini Content Creator benefit my content creation process?</h4>
                        <p class="text-gray-500 mb-4">Writegemini Content Creator streamlines content creation by harnessing the power of artificial intelligence. It accelerates the writing process, suggests improvements, and ensures the creation of high-quality content within minutes. Whether you\'re a blogger, marketer, or social media enthusiast, this platform enhances your efficiency and helps you produce engaging content effortlessly.</p>
                    </div>
                </div>
                <div class="w-full sm:w-1/2 lg:w-1/3 px-4 mb-8">
                    <div
                        class="flex flex-col h-full max-w-xs mx-auto md:max-w-none px-6 py-9 md:pl-9 md:pr-12 bg-white rounded-3xl shadow-lg">
                        <h4 class="text-xl font-semibold text-gray-900 mb-4">What types of content can I create using Writegemini Content Creator?</h4>
                        <p class="text-gray-500 mb-4">Pretium ac auctor quis urna orci feugiat. Vulputate tellus velit tellus orci auctor vel nulla facilisi ut. Writegemini Content Creator is versatile and supports the creation of various content types. You can use it to generate engaging blog posts, articles, social media posts, and more. The platform\'s AI capabilities adapt to different writing styles and topics, making it a valuable tool for a wide range of content creators.</p>
                    </div>
                </div>
                <div class="w-full sm:w-1/2 lg:w-1/3 px-4 mb-8">
                    <div
                        class="flex flex-col h-full max-w-xs mx-auto md:max-w-none px-6 py-9 md:pl-9 md:pr-12 bg-white rounded-3xl shadow-lg">
                        <h4 class="text-xl font-semibold text-gray-900 mb-4">How user-friendly is Writegemini Content Creator for beginners?</h4>
                        <p class="text-gray-500 mb-4">Writegemini Content Creator is designed with user-friendliness in mind. Its intuitive interface and straightforward features make it accessible for both beginners and experienced content creators. The platform provides guidance throughout the content creation process, ensuring that even those new to AI-powered tools can easily navigate and produce compelling content.</p>
                    </div>
                </div>
                <div class="w-full sm:w-1/2 lg:w-1/3 px-4 mb-8">
                    <div
                        class="flex flex-col h-full max-w-xs mx-auto md:max-w-none px-6 py-9 md:pl-9 md:pr-12 bg-white rounded-3xl shadow-lg">
                        <h4 class="text-xl font-semibold text-gray-900 mb-4">Can I customize the AI-generated content to fit my unique style and preferences?</h4>
                        <p class="text-gray-500 mb-4">Absolutely! Writegemini Content Creator allows users to customize and fine-tune the AI-generated content according to their unique style and preferences. You have the flexibility to make edits, add personal touches, and ensure that the final output aligns perfectly with your individual writing style and requirements.</p>
                    </div>
                </div>
                <div class="w-full sm:w-1/2 lg:w-1/3 px-4 mb-8">
                    <div
                        class="flex flex-col h-full max-w-xs mx-auto md:max-w-none px-6 py-9 md:pl-9 md:pr-12 bg-white rounded-3xl shadow-lg">
                        <h4 class="text-xl font-semibold text-gray-900 mb-4">How often are updates and new features released for Writegemini Content Creator?</h4>
                        <p class="text-gray-500 mb-4">Writegemini Content Creator is typically updated regularly to introduce new features, improvements, and enhancements. Keeping the platform up-to-date ensures that users have access to the latest advancements in AI technology, making their content creation experience even more efficient and effective.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>',
            'page_title' => 'FAQs - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '317109101703740',
            'name' => 'Privacy Policy',
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'body' => '<section class="relative py-10 lg:py-10 overflow-hidden">
                        <img class="absolute top-0 left-0 mt-10" src="themes/modern-orange/assets/images/content/stars-left-top.svg" alt="">
                        <img class="absolute bottom-0 right-0" src="themes/modern-orange/assets/images/content/orange-light-right.png"
                            alt="">
                        <div class="relative container px-4 mx-auto">
                            <div class="text-center mb-18">
                                <h1 class="font-heading text-5xl xs:text-6xl md:text-7xl font-bold text-gray-900 mb-4">
                                    <span contenteditable="false" class="animated-gradient-text">Privacy Policy</span>
                                </h1>
                            </div>
                            <div class="max-w-lg lg:max-w-3xl xl:max-w-5xl mx-auto">
                                <div class="flex flex-wrap -mx-4">
                                    <div class="w-full px-4 mb-12 lg:mb-0">
                                        <div class="w-lg">
                                            <p class="text-lg text-gray-700 mb-10">
                                                Thank you for choosing AITools and trusting us with your personal information. This Privacy Policy outlines how we collect, use, share, and protect your information when you use our services.
                                            </p>
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">1. Information We Collect:</h3>
                    
                                            <p class="text-lg text-gray-700 mb-10">We may collect personal information such as your name, email address, and other relevant details when you voluntarily provide it to us while using our website or services.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">2. How We Use Your Information:</h3>
                    
                                            <p class="text-lg text-gray-700 mb-10">We use the information collected to provide and improve our services, personalize your experience, communicate with you, and send updates about our products and services. We do not sell or share your personal information with third parties for marketing purposes.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">3. Information Security:</h3>
                    
                                            <p class="text-lg text-gray-700 mb-10">We take the security of your personal information seriously. We implement reasonable security measures to protect against unauthorized access, disclosure, alteration, or destruction of your information.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">4. Cookies:</h3>
                    
                                            <p class="text-lg text-gray-700 mb-10">We may use cookies to enhance your user experience. You can choose to disable cookies through your browser settings, but please note that some features of our website may not function properly as a result.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">5. Third-Party Links:</h3>
                    
                                            <p class="text-lg text-gray-700 mb-10">Our website may contain links to third-party websites. Please be aware that we are not responsible for the privacy practices of these external sites. We encourage you to read the privacy policies of these websites when you leave our site.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">6. Children\'s Privacy:</h3>
                    
                                            <p class="text-lg text-gray-700 mb-10">Our services are not intended for individuals under the age of 13. We do not knowingly collect personal information from children. If you are a parent or guardian and believe your child has provided us with personal information, please contact us, and we will take steps to remove such information.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">7. Changes to This Privacy Policy:</h3>
                    
                                            <p class="text-lg text-gray-700 mb-10">We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new policy on this page. We recommend checking this page periodically to stay informed about how we are protecting and using your information.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">8. Contact Us:</h3>
                    
                                            <p class="text-lg text-gray-700 mb-10">If you have any questions or concerns about our Privacy Policy, please contact us at <a class="text-orange-700" href="mailto:hello@yourdomain.com">hello@yourdomain.com</a>.</p>
                    
                                            <p class="text-lg text-gray-700 mb-10">By using our website or services, you agree to the terms outlined in this Privacy Policy.</p>
                    
                                            <p class="text-lg text-gray-700 mb-10">Thank you for choosing AITools.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>',
            'page_title' => 'Privacy Policy - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '317109101703740',
            'name' => 'Refund Policy',
            'title' => 'Refund Policy',
            'slug' => 'refund-policy',
            'body' => '<section class="relative py-10 lg:py-10 overflow-hidden">
                        <img class="absolute top-0 left-0 mt-10" src="themes/modern-orange/assets/images/content/stars-left-top.svg" alt="">
                        <img class="absolute bottom-0 right-0" src="themes/modern-orange/assets/images/content/orange-light-right.png"
                            alt="">
                        <div class="relative container px-4 mx-auto">
                            <div class="text-center mb-18">
                                <h1 class="font-heading text-5xl xs:text-6xl md:text-7xl font-bold text-gray-900 mb-4">
                                    <span contenteditable="false" class="animated-gradient-text">Refund Policy</span>
                                </h1>
                            </div>
                            <div class="max-w-lg lg:max-w-3xl xl:max-w-5xl mx-auto">
                                <div class="flex flex-wrap -mx-4">
                                    <div class="w-full px-4 mb-12 lg:mb-0">
                                        <div class="w-lg">
                                            <p class="text-lg text-gray-700 mb-10">
                                                Thank you for choosing AITools. We value your satisfaction and aim to provide a positive experience with our products. Please review our refund policy below.
                                            </p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">1. Refund Eligibility:</h3>
                                            <p class="text-lg text-gray-700 mb-10">We offer refunds under the following circumstances:</p>
                                            <p class="text-lg text-gray-700 mb-10">Technical Issues: If you encounter technical issues or errors that significantly hinder your ability to use AITools, and our support team is unable to resolve the problem within a reasonable timeframe.</p>
                                            <p class="text-lg text-gray-700 mb-10">Unsatisfactory Service: If you are dissatisfied with the service provided by AITools and have communicated your concerns to our support team without a satisfactory resolution.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">2. Refund Request Process:</h3>
                                            <p class="text-lg text-gray-700 mb-10">To request a refund, please follow these steps:</p>
                                            <p class="text-lg text-gray-700 mb-10">Contact our customer support team at <a class="text-orange-700" href="mailto:hello@yourdomain.com">hello@yourdomain.com</a> within 7 days of the purchase date.</p>
                                            <p class="text-lg text-gray-700 mb-10">Provide a detailed explanation of the reason for your refund request.</p>
                                            <p class="text-lg text-gray-700 mb-10">Allow our support team a reasonable timeframe to address and resolve the issue. If a resolution cannot be reached, we will proceed with the refund process.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">3. Refund Amount:</h3>
                                            <p class="text-lg text-gray-700 mb-10">Refunds, if approved, will be issued for the amount paid during the original purchase. Any additional charges or fees incurred during the transaction may not be refundable.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">4. Refund Method:</h3>
                                            <p class="text-lg text-gray-700 mb-10">Refunds will typically be processed using the same method as the original payment. If this is not possible, alternative arrangements will be made in consultation with the user.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">5. Exceptions:</h3>
                                            <p class="text-lg text-gray-700 mb-10">Refunds will not be granted if the user violates our terms of service.</p>
                                            <p class="text-lg text-gray-700 mb-10">Refunds are not applicable for subscription services beyond the initial trial period.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">6. Contact Us:</h3>
                                            <p class="text-lg text-gray-700 mb-10">If you have any questions or concerns about our refund policy, please contact us at <a class="text-orange-700" href="mailto:hello@yourdomain.com">hello@yourdomain.com</a>.</p>
                                            <p class="text-lg text-gray-700 mb-10">By using our services, you agree to the terms outlined in this Refund Policy.</p>
                                            <p class="text-lg text-gray-700 mb-10">Thank you for choosing AITools.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>',
            'page_title' => 'Refund Policy - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '317109101703740',
            'name' => 'Terms and Conditions',
            'title' => 'Terms and Conditions',
            'slug' => 'terms-and-conditions',
            'body' => '<section class="relative py-10 lg:py-10 overflow-hidden">
                        <img class="absolute top-0 left-0 mt-10" src="themes/modern-orange/assets/images/content/stars-left-top.svg" alt="">
                        <img class="absolute bottom-0 right-0" src="themes/modern-orange/assets/images/content/orange-light-right.png"
                            alt="">
                        <div class="relative container px-4 mx-auto">
                            <div class="text-center mb-18">
                                <h1 class="font-heading text-5xl xs:text-6xl md:text-7xl font-bold text-gray-900 mb-4">
                                    <span contenteditable="false" class="animated-gradient-text">Terms and Conditions</span>
                                </h1>
                            </div>
                            <div class="max-w-lg lg:max-w-3xl xl:max-w-5xl mx-auto">
                                <div class="flex flex-wrap -mx-4">
                                    <div class="w-full px-4 mb-12 lg:mb-0">
                                        <div class="w-lg">
                                            <p class="text-lg text-gray-700 mb-10">
                                                Thank you for choosing AITools. Please read these Terms and Conditions carefully before using our services.
                                            </p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">1. Acceptance of Terms:</h3>
                                            <p class="text-lg text-gray-700 mb-10">By accessing or using AITools, you agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, please do not use our services.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">2. Use of Services:</h3>
                                            <p class="text-lg text-gray-700 mb-10">You may use our services for personal or business purposes, as intended by AITools. You agree not to engage in any activity that interferes with or disrupts the functionality of our platform.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">3. Intellectual Property:</h3>
                                            <p class="text-lg text-gray-700 mb-10">The content, features, and functionality of AITools are the property of AITools and are protected by copyright, trademark, and other intellectual property laws. You may not reproduce, distribute, modify, or create derivative works without our express consent.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">4. User Accounts:</h3>
                                            <p class="text-lg text-gray-700 mb-10">To access certain features, you may be required to create an account. You are responsible for maintaining the confidentiality of your account information and agree to notify us immediately of any unauthorized use.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">5. Privacy Policy:</h3>
                                            <p class="text-lg text-gray-700 mb-10">Your use of AITools is also governed by our <a class="text-orange-700" href="/privacy-policy" target="_blank">Privacy Policy</a>. By using our services, you consent to the terms outlined in the Privacy Policy.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">6. Refund Policy:</h3>
                                            <p class="text-lg text-gray-700 mb-10">Our Refund Policy outlines the conditions under which refunds may be granted. Please review our Refund Policy for more details.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">7. Support:</h3>
                                            <p class="text-lg text-gray-700 mb-10">For any inquiries, concerns, or support-related issues, please contact our customer support team at <a class="text-orange-700" href="mailto:hello@yourdomain.com">hello@yourdomain.com</a>.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">8. Changes to Terms:</h3>
                                            <p class="text-lg text-gray-700 mb-10">We reserve the right to update or modify these Terms and Conditions at any time. The date of the latest update will be displayed at the beginning of this document. It is your responsibility to review these terms periodically.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">9. Termination:</h3>
                                            <p class="text-lg text-gray-700 mb-10">We reserve the right to terminate or suspend your access to AITools at our discretion, without prior notice, for any violation of these Terms and Conditions.</p>
                    
                                            <h3 class="text-2xl lg:text-4xl mb-4 text-gray-900 tracking-2xl">10. Governing Law:</h3>
                                            <p class="text-lg text-gray-700 mb-10">These Terms and Conditions are governed by and construed in accordance with the laws of India.</p>
                                            <p class="text-lg text-gray-700 mb-10">Thank you for choosing AITools.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>',
            'page_title' => 'Terms and Conditions - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);

        DB::table('pages')->insert([
            'theme_id' => '317109101703740',
            'name' => 'Pricing',
            'title' => 'Pricing',
            'slug' => 'pricing',
            'body' => '<div class="max-w-2xl lg:max-w-5xl mx-auto mb-18 text-center">
                        <span
                            class="inline-block py-1 px-3 mb-4 text-xs font-semibold bg-orange-900 text-orange-100 rounded-full">OUR PLANS</span>
                        <h1 class="font-heading text-4xl sm:text-6xl lg:text-7xl font-bold text-gray-900 mb-10">
                            <span>Choose a plan for a more</span>
                            <span class="font-serif italic">advanced</span>
                            <span>business</span>
                        </h1>
                    </div>',
            'page_title' => 'Pricing - Create High-Quality Content in Minutes',
            'description' => 'AI Tools Content Creator is a powerful online platform that helps you create compelling content using cutting-edge artificial intelligence technology. With AI-powered tools and features, you can create high-quality blog posts, articles, social media posts, and more in a matter of minutes. Start creating engaging content today with AI Tools Content Creator!',
            'keywords' => 'AI Tools Content Creator, artificial intelligence, content creation, blog posts, articles, social media, online platform, AI-powered tools, high-quality content, engaging content.'
        ]);
    }
}
