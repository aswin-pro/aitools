<?php

namespace App\Http\Controllers\Website;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShareController extends Controller
{
    // Blog post share
    public function shareToFacebook($slug)
    {
        // Blog details
        $blogDetails = Blog::where('slug', $slug)->where('status', 1)->first();

        $url = url('blog/'.$blogDetails->slug);;
        $title = $blogDetails->title;
        $description = $blogDetails->description;
        $image = asset($blogDetails->cover_image);

        // Create a share URL for Facebook
        $facebookShareUrl = "https://www.facebook.com/sharer/sharer.php?" . http_build_query([
            'u' => $url,
            'quote' => $title,  // Optional: Include a quote parameter to add a predefined text when sharing
            'description' => $description,  // Optional: Include the description parameter for the shared content
            'picture' => $image  // Optional: Include the image parameter for the shared content
        ]);

        return redirect()->away($facebookShareUrl);
    }

    public function shareToTwitter($slug)
    {
        // Blog details
        $blogDetails = Blog::where('slug', $slug)->where('status', 1)->first();

        $url = url('blog/'.$blogDetails->slug);;
        $title = $blogDetails->title;
        $description = $blogDetails->description;
        $image = asset($blogDetails->cover_image);

        // Create a share URL for Twitter
        $twitterShareUrl = "https://twitter.com/intent/tweet?" . http_build_query([
            'text' => $title,  // The text to be included in the tweet
            'url' => $url,  // The URL to be included in the tweet
            'description' => $description,  // Optional: Description of the shared content
            'image' => $image  // Optional: URL of the image to be included in the tweet
        ]);

        return redirect()->away($twitterShareUrl);
    }

    public function shareToLinkedIn($slug)
    {
        // Blog details
        $blogDetails = Blog::where('slug', $slug)->where('status', 1)->first();

        $url = url('blog/'.$blogDetails->slug);;
        $title = $blogDetails->title;
        $description = $blogDetails->description;
        $image = asset($blogDetails->cover_image);
        $source = url('blog/'.$blogDetails->slug);;

        // Create a meta tag URL for LinkedIn
        $linkedInMetaUrl = "https://www.linkedin.com/shareArticle?" . http_build_query([
            'url' => $url,
            'title' => $title,
            'summary' => $description,
            'source' => $source,
            'mini' => 'true',  // This parameter ensures that the LinkedIn share dialog is in a smaller format
            'images' => $image  // You can specify multiple images separated by commas if needed
        ]);

        $linkedInMetaUrl = "https://www.linkedin.com/shareArticle?url={$url}&title={$title}&summary={$description}&source{$source}";

        return redirect()->away($linkedInMetaUrl);
    }

    public function shareToInstagram($slug)
    {
        // Blog details
        $blogDetails = Blog::where('slug', $slug)->where('status', 1)->first();

        $url = url('blog/'.$blogDetails->slug);;
        $title = $blogDetails->title;
        $description = $blogDetails->description;
        $image = asset($blogDetails->cover_image);

        // Generate Instagram share caption with post details
        $instagramCaption = "Caption: {$title}. Description: {$description}. Visit the full post at {$url}";

        return redirect()->away($instagramCaption);
    }

    public function shareToWhatsApp($slug)
    {
        // Blog details
        $blogDetails = Blog::where('slug', $slug)->where('status', 1)->first();

        $url = url('blog/'.$blogDetails->slug);;
        $title = $blogDetails->title;
        $description = $blogDetails->description;
        $image = asset($blogDetails->cover_image);

        $whatsAppShareLink = "https://wa.me/?text=" . urlencode("Check out this post: {$title} - {$description} - {$url}");

        return redirect()->away($whatsAppShareLink);
    }
}
