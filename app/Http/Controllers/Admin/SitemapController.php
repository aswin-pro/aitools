<?php

namespace App\Http\Controllers\Admin;

use App\Models\Config;
use App\Models\Setting;
use Spatie\Sitemap\Sitemap;
use Illuminate\Http\Request;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class SitemapController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // Generating a sitemap
    public function index()
    {
        // Queries
        $settings = Setting::first();
        $config   = Config::get();

        // return view('admin.pages.sitemap.index', compact('settings', 'config'));

        return Inertia::render('admin/system/sitemap/index', compact('settings', 'config'));
    }

    // Generate sitemap
    public function generate(Request $request)
    {
        $categories = $request->categories ?? null;

        // Create a new Sitemap instance
        $sitemap = Sitemap::create();

        // Generate sitemap for website pages
        if (in_array('pages', $categories) || in_array('all', $categories)) {
            $pages = DB::table('pages')->select('slug')->groupBy('slug')->get();
            foreach ($pages as $page) {
                $sitemap->add(Url::create(url($page->slug == "home" ? "/" : $page->slug)));
            }
        }

        // Generate sitemap for blogs
        if (in_array('blog', $categories) || in_array('all', $categories)) {
            $sitemap->add(Url::create(url('/blogs')));
            $blogs = DB::table('blogs')->get();
            foreach ($blogs as $blog) {
                $sitemap->add(Url::create(url('blog/' . $blog->slug)));
            }
        }

        // Generate sitemap for web tools
        if (in_array('webtools', $categories)) {
            $webtools = [
                '/html-beautifier',
                '/html-minifier',
                '/css-beautifier',
                '/css-minifier',
                '/js-beautifier',
                '/js-minifier',
                '/random-password-generator',
                '/bcrypt-password-generator',
                '/md5-password-generator',
                '/random-word-generator',
                '/text-counter',
                '/lorem-generator',
                '/emojies',
                '/dns-lookup',
                '/ip-lookup',
                '/whois-lookup'
            ];
            foreach ($webtools as $tool) {
                $sitemap->add(Url::create(url($tool)));
            }
        }

        // Save sitemap to public directory
        $sitemap->writeToFile(public_path('sitemap.xml'));

        // return redirect()->route('admin.sitemap')->with('success', trans('Generated!'));
    }
}
