<?php

namespace App\Http\Controllers\Website;

use App\Models\Blog;
use App\Models\Page;
use App\Models\Plan;
use App\Models\Config;
use App\Models\Setting;
use App\Models\Currency;
use App\Models\IpAddress;
use App\Classes\NonStorage;
use App\Providers\AppConfig;
use Illuminate\Http\Request;
use App\Models\CustomTemplate;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\AiImages;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Schema;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\OpenGraph;

class WebController extends Controller
{
    // Web Index
    public function webIndex()
    {
        // Queries
        $config = Config::get();

        if (!Schema::hasTable('themes')) {
            $appConfig = new AppConfig;
            $appConfig->alteration();
        }

        // Check website
        if ($config[43]->config_value == "yes") {
            // Plans
            $page = Page::where('theme_id', $config[48]->config_value)->where('slug', 'home')->where('status', 1)->get();

            if (count($page) == 0) {
                $page = Page::where('theme_id', $config[48]->config_value)->where('slug', 'hero')->where('status', 1)->get();
            }

            // Check page
            if (!$page->isEmpty()) {
                $plans = Plan::where('status', 1)->where('is_private', '0')->get();
                $config = Config::get();
                $currency = Currency::where('iso_code', $config['1']->config_value)->first();
                $setting = Setting::where('status', 1)->first();

                // Tools
                $tools = CustomTemplate::where('status', 1)->limit(6)->get();

                // Images
                $images = AiImages::where('format', 'url')->where('status', 1)->limit(4)->orderBy('id', 'desc')->get();

                // Check plan for free
                $planPrices = [];
                for ($j = 0; $j < count($plans); $j++) {
                    $planPrices[$j] = $plans[$j]->price;
                }

                // Credit card required
                $requiredCreditCard = false;
                if (in_array("0.00", $planPrices)) {
                    $requiredCreditCard = true;
                }

                // Seo Tools
                SEOTools::setTitle($page[0]->page_title);
                SEOTools::setDescription($page[0]->description);

                SEOMeta::setTitle($page[0]->page_title);
                SEOMeta::setDescription($page[0]->description);
                SEOMeta::addMeta('article:section', $page[0]->name . ' - ' . $page[0]->description, 'property');
                SEOMeta::addKeyword([$page[0]->keywords]);

                OpenGraph::setTitle($page[0]->page_title);
                OpenGraph::setDescription($page[0]->description);
                OpenGraph::setUrl(URL::full());
                OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

                JsonLd::setTitle($page[0]->page_title);
                JsonLd::setDescription($page[0]->description);
                JsonLd::addImage(asset($setting->site_logo));

                // Return values
                $returnValues = compact('plans', 'config', 'currency', 'setting', 'requiredCreditCard', 'tools', 'images');

                // Check selected theme
                if ($config[48]->config_value == "513402991882314") {
                    // view
                    return view("website.classic.index", $returnValues);
                } else if ($config[48]->config_value == "330599619570398") {
                    // view
                    return view("website.modern.index", $returnValues);
                } else if ($config[48]->config_value == "317109101703740") {
                    // view
                    return view("website.modern-orange.index", $returnValues);
                }
            } else {
                abort(404);
            }
        } else {
            return redirect('/login');
        }
    }

    // Web Tools 
    public function webTools()
    {
        // Queries
        $config = Config::get();

        // Check website
        if ($config[43]->config_value == "yes") {
            // Queries
            $page = Page::where('theme_id', $config[48]->config_value)->where('slug', 'tools')->where('status', 1)->get();

            // Check page
            if (!$page->isEmpty()) {
                // Templates
                $templates = CustomTemplate::where('status', 1)->get();

                // Queries
                $config = Config::get();
                $setting = Setting::where('status', 1)->first();

                // Seo Tools
                SEOTools::setTitle($page[0]->page_title);
                SEOTools::setDescription($page[0]->description);

                SEOMeta::setTitle($page[0]->page_title);
                SEOMeta::setDescription($page[0]->description);
                SEOMeta::addMeta('article:section', trans('Tools'), 'property');
                SEOMeta::addKeyword([$page[0]->keywords]);

                OpenGraph::setTitle($page[0]->page_title);
                OpenGraph::setDescription($page[0]->description);
                OpenGraph::setUrl(URL::full());
                OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

                JsonLd::setTitle($page[0]->page_title);
                JsonLd::setDescription($page[0]->description);
                JsonLd::addImage(asset($setting->site_logo));

                // Return values
                $returnValues = compact('templates', 'config', 'setting');

                // Check selected theme
                if ($config[48]->config_value == "513402991882314") {
                    // view
                    return view("website.classic.pages.tools", $returnValues);
                } else if ($config[48]->config_value == "330599619570398") {
                    // view
                    return view("website.modern.pages.tools", $returnValues);
                } else if ($config[48]->config_value == "317109101703740") {
                    // view
                    return view("website.modern-orange.pages.tools", $returnValues);
                }
            } else {
                abort(404);
            }
        } else {
            return redirect('/login');
        }
    }

    // Web Features 
    public function webFeatures()
    {
        // Queries
        $config = Config::get();

        // Check website
        if ($config[43]->config_value == "yes") {
            // Queries
            $page = Page::where('theme_id', $config[48]->config_value)->where('slug', 'features')->where('status', 1)->get();

            // Check page
            if (!$page->isEmpty()) {
                $config = Config::get();
                $setting = Setting::where('status', 1)->first();

                // Seo Tools
                SEOTools::setTitle($page[0]->page_title);
                SEOTools::setDescription($page[0]->description);

                SEOMeta::setTitle($page[0]->page_title);
                SEOMeta::setDescription($page[0]->description);
                SEOMeta::addMeta('article:section', trans('Features'), 'property');
                SEOMeta::addKeyword([$page[0]->keywords]);

                OpenGraph::setTitle($page[0]->page_title);
                OpenGraph::setDescription($page[0]->description);
                OpenGraph::setUrl(URL::full());
                OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

                JsonLd::setTitle($page[0]->page_title);
                JsonLd::setDescription($page[0]->description);
                JsonLd::addImage(asset($setting->site_logo));

                // Return values
                $returnValues = compact('config', 'setting');

                // Check selected theme
                if ($config[48]->config_value == "513402991882314") {
                    // view
                    return view("website.classic.pages.features", $returnValues);
                } else if ($config[48]->config_value == "330599619570398") {
                    // view
                    return view("website.modern.pages.features", $returnValues);
                } else if ($config[48]->config_value == "317109101703740") {
                    // view
                    return view("website.modern-orange.pages.features", $returnValues);
                }
            } else {
                abort(404);
            }
        } else {
            return redirect('/login');
        }
    }

    // Web About 
    public function webAbout()
    {
        // Queries
        $config = Config::get();

        // Check website
        if ($config[43]->config_value == "yes") {
            // Queries
            $page = Page::where('theme_id', $config[48]->config_value)->where('slug', 'about')->where('status', 1)->get();

            // Check page
            if (!$page->isEmpty()) {
                $config = Config::get();
                $setting = Setting::where('status', 1)->first();

                // Seo Tools
                SEOTools::setTitle($page[0]->page_titlen);
                SEOTools::setDescription($page[0]->description);

                SEOMeta::setTitle($page[0]->page_title);
                SEOMeta::setDescription($page[0]->description);
                SEOMeta::addMeta('article:section', $page[0]->name . ' - ' . $page[0]->description, 'property');
                SEOMeta::addKeyword([$page[0]->keywords]);

                OpenGraph::setTitle($page[0]->page_title);
                OpenGraph::setDescription($page[0]->description);
                OpenGraph::setUrl(URL::full());
                OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

                JsonLd::setTitle($page[0]->page_title);
                JsonLd::setDescription($page[0]->description);
                JsonLd::addImage(asset($setting->site_logo));

                // Return values
                $returnValues = compact('config', 'setting');

                // Check selected theme
                if ($config[48]->config_value == "513402991882314") {
                    // view
                    return view("website.classic.pages.about", $returnValues);
                } else if ($config[48]->config_value == "330599619570398") {
                    // view
                    return view("website.modern.pages.about", $returnValues);
                } else if ($config[48]->config_value == "317109101703740") {
                    // view
                    return view("website.modern-orange.pages.about", $returnValues);
                }
            } else {
                abort(404);
            }
        } else {
            return redirect('/login');
        }
    }

    // Web Pricing
    public function webPricing()
    {
        // Queries
        $config = Config::get();

        // Check website
        if ($config[43]->config_value == "yes") {
            // Plans
            $page = Page::where('theme_id', $config[48]->config_value)->where('slug', 'pricing')->where('status', 1)->get();

            // Check page
            if (!$page->isEmpty()) {
                $plans = Plan::where('status', 1)->where('is_private', '0')->get();
                $config = Config::get();
                $currency = Currency::where('iso_code', $config['1']->config_value)->first();
                $setting = Setting::where('status', 1)->first();

                // Seo Tools
                SEOTools::setTitle($page[0]->page_title);
                SEOTools::setDescription($page[0]->description);

                SEOMeta::setTitle($page[0]->page_title);
                SEOMeta::setDescription($page[0]->description);
                SEOMeta::addMeta('article:section', $page[0]->name . ' - ' . $page[0]->description, 'property');
                SEOMeta::addKeyword([$page[0]->keywords]);

                OpenGraph::setTitle($page[0]->page_title);
                OpenGraph::setDescription($page[0]->description);
                OpenGraph::setUrl(URL::full());
                OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

                JsonLd::setTitle($page[0]->page_title);
                JsonLd::setDescription($page[0]->description);
                JsonLd::addImage(asset($setting->site_logo));

                // Return values
                $returnValues = compact('plans', 'config', 'currency', 'setting');

                // Check selected theme
                if ($config[48]->config_value == "513402991882314") {
                    // view
                    return view("website.classic.pages.pricing", $returnValues);
                } else if ($config[48]->config_value == "330599619570398") {
                    // view
                    return view("website.modern.pages.pricing", $returnValues);
                } else if ($config[48]->config_value == "317109101703740") {
                    // view
                    return view("website.modern-orange.pages.pricing", $returnValues);
                }
            } else {
                abort(404);
            }
        } else {
            return redirect('/login');
        }
    }

    // Web Contact
    public function webContact()
    {
        // Queries
        $config = Config::get();

        // Check website
        if ($config[43]->config_value == "yes") {
            // Queries
            $page = Page::where('theme_id', $config[48]->config_value)->where('slug', 'contact')->where('status', 1)->get();

            // Check page
            if (!$page->isEmpty()) {
                $config = Config::get();
                $setting = Setting::where('status', 1)->first();

                // Seo Tools
                SEOTools::setTitle($page[0]->page_title);
                SEOTools::setDescription($page[0]->description);

                SEOMeta::setTitle($page[0]->page_title);
                SEOMeta::setDescription($page[0]->description);
                SEOMeta::addMeta('article:section', $page[0]->name . ' - ' . $page[0]->description, 'property');
                SEOMeta::addKeyword([$page[0]->keywords]);

                OpenGraph::setTitle($page[0]->page_title);
                OpenGraph::setDescription($page[0]->description);
                OpenGraph::setUrl(URL::full());
                OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

                JsonLd::setTitle($page[0]->page_title);
                JsonLd::setDescription($page[0]->description);
                JsonLd::addImage(asset($setting->site_logo));

                // Return values
                $returnValues = compact('config', 'setting');

                // Check selected theme
                if ($config[48]->config_value == "513402991882314") {
                    // view
                    return view("website.classic.pages.contact", $returnValues);
                } else if ($config[48]->config_value == "330599619570398") {
                    // view
                    return view("website.modern.pages.contact", $returnValues);
                } else if ($config[48]->config_value == "317109101703740") {
                    // view
                    return view("website.modern-orange.pages.contact", $returnValues);
                }
            } else {
                abort(404);
            }
        } else {
            return redirect('/login');
        }
    }

    // Web FAQs
    public function webFAQ()
    {
        // Queries
        $config = Config::get();

        // Check website
        if ($config[43]->config_value == "yes") {
            // Queries
            $page = Page::where('theme_id', $config[48]->config_value)->where('slug', 'faq')->where('status', 1)->get();

            // Check page
            if (!$page->isEmpty()) {
                $config = Config::get();
                $setting = Setting::where('status', 1)->first();

                // Seo Tools
                SEOTools::setTitle($page[0]->page_title);
                SEOTools::setDescription($page[0]->description);

                SEOMeta::setTitle($page[0]->page_title);
                SEOMeta::setDescription($page[0]->description);
                SEOMeta::addMeta('article:section', $page[0]->name . ' - ' . $page[0]->description, 'property');
                SEOMeta::addKeyword([$page[0]->keywords]);

                OpenGraph::setTitle($page[0]->page_title);
                OpenGraph::setDescription($page[0]->description);
                OpenGraph::setUrl(URL::full());
                OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

                JsonLd::setTitle($page[0]->page_title);
                JsonLd::setDescription($page[0]->description);
                JsonLd::addImage(asset($setting->site_logo));

                // Return values
                $returnValues = compact('config', 'setting');

                // Check selected theme
                if ($config[48]->config_value == "513402991882314") {
                    // view
                    return view("website.classic.pages.faq", $returnValues);
                } else if ($config[48]->config_value == "330599619570398") {
                    // view
                    return view("website.modern.pages.faq", $returnValues);
                } else if ($config[48]->config_value == "317109101703740") {
                    // view
                    return view("website.modern-orange.pages.faq", $returnValues);
                }
            } else {
                abort(404);
            }
        } else {
            return redirect('/login');
        }
    }

    // Web Privacy
    public function webPrivacy()
    {
        // Queries
        $config = Config::get();

        // Check website
        if ($config[43]->config_value == "yes") {
            // Queries
            $page = Page::where('theme_id', $config[48]->config_value)->where('slug', 'privacy-policy')->where('status', 1)->get();

            // Check page
            if (!$page->isEmpty()) {
                $config = Config::get();
                $setting = Setting::where('status', 1)->first();

                // Seo Tools
                SEOTools::setTitle($page[0]->page_title);
                SEOTools::setDescription($page[0]->description);

                SEOMeta::setTitle($page[0]->page_title);
                SEOMeta::setDescription($page[0]->description);
                SEOMeta::addMeta('article:section', $page[0]->name . ' - ' . $page[0]->description, 'property');
                SEOMeta::addKeyword([$page[0]->keywords]);

                OpenGraph::setTitle($page[0]->page_title);
                OpenGraph::setDescription($page[0]->description);
                OpenGraph::setUrl(URL::full());
                OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

                JsonLd::setTitle($page[0]->page_title);
                JsonLd::setDescription($page[0]->description);
                JsonLd::addImage(asset($setting->site_logo));

                // Return values
                $returnValues = compact('config', 'setting');

                // Check selected theme
                if ($config[48]->config_value == "513402991882314") {
                    // view
                    return view("website.classic.pages.privacy", $returnValues);
                } else if ($config[48]->config_value == "330599619570398") {
                    // view
                    return view("website.modern.pages.privacy", $returnValues);
                } else if ($config[48]->config_value == "317109101703740") {
                    // view
                    return view("website.modern-orange.pages.privacy", $returnValues);
                }
            } else {
                abort(404);
            }
        } else {
            return redirect('/login');
        }
    }

    // Web Refund
    public function webRefund()
    {
        // Queries
        $config = Config::get();

        // Check website
        if ($config[43]->config_value == "yes") {
            // Queries
            $page = Page::where('theme_id', $config[48]->config_value)->where('slug', 'refund-policy')->where('status', 1)->get();

            // Check page
            if (!$page->isEmpty()) {
                $config = Config::get();
                $setting = Setting::where('status', 1)->first();

                // Seo Tools
                SEOTools::setTitle($page[0]->page_title);
                SEOTools::setDescription($page[0]->description);

                SEOMeta::setTitle($page[0]->page_title);
                SEOMeta::setDescription($page[0]->description);
                SEOMeta::addMeta('article:section', $page[0]->name . ' - ' . $page[0]->description, 'property');
                SEOMeta::addKeyword([$page[0]->keywords]);

                OpenGraph::setTitle($page[0]->page_title);
                OpenGraph::setDescription($page[0]->description);
                OpenGraph::setUrl(URL::full());
                OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

                JsonLd::setTitle($page[0]->page_title);
                JsonLd::setDescription($page[0]->description);
                JsonLd::addImage(asset($setting->site_logo));

                // Return values
                $returnValues = compact('config', 'setting');

                // Check selected theme
                if ($config[48]->config_value == "513402991882314") {
                    // view
                    return view("website.classic.pages.refund", $returnValues);
                } else if ($config[48]->config_value == "330599619570398") {
                    // view
                    return view("website.modern.pages.refund", $returnValues);
                } else if ($config[48]->config_value == "317109101703740") {
                    // view
                    return view("website.modern-orange.pages.refund", $returnValues);
                }
            } else {
                abort(404);
            }
        } else {
            return redirect('/login');
        }
    }

    // Web Terms
    public function webTerms()
    {
        // Queries
        $config = Config::get();

        // Check website
        if ($config[43]->config_value == "yes") {
            // Queries
            $page = Page::where('theme_id', $config[48]->config_value)->where('slug', 'terms-and-conditions')->where('status', 1)->get();

            // Check page
            if (!$page->isEmpty()) {
                $config = Config::get();
                $setting = Setting::where('status', 1)->first();

                // Seo Tools
                SEOTools::setTitle($page[0]->page_title);
                SEOTools::setDescription($page[0]->description);

                SEOMeta::setTitle($page[0]->page_title);
                SEOMeta::setDescription($page[0]->description);
                SEOMeta::addMeta('article:section', $page[0]->name . ' - ' . $page[0]->description, 'property');
                SEOMeta::addKeyword([$page[0]->keywords]);

                OpenGraph::setTitle($page[0]->page_title);
                OpenGraph::setDescription($page[0]->description);
                OpenGraph::setUrl(URL::full());
                OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

                JsonLd::setTitle($page[0]->page_title);
                JsonLd::setDescription($page[0]->description);
                JsonLd::addImage(asset($setting->site_logo));

                // Return values
                $returnValues = compact('config', 'setting');

                // Check selected theme
                if ($config[48]->config_value == "513402991882314") {
                    // view
                    return view("website.classic.pages.terms", $returnValues);
                } else if ($config[48]->config_value == "330599619570398") {
                    // view
                    return view("website.modern.pages.terms", $returnValues);
                } else if ($config[48]->config_value == "317109101703740") {
                    // view
                    return view("website.modern-orange.pages.terms", $returnValues);
                }
            } else {
                abort(404);
            }
        } else {
            return redirect('/login');
        }
    }

    // Custom pages
    public function customPage($id)
    {
        // Queries
        $config = Config::get();

        // Check website
        if ($config[43]->config_value == "yes") {
            // Get page details
            $page = Page::where('theme_id', $config[48]->config_value)->where('slug', $id)->where('status', 1)->first();

            $config = Config::get();
            $setting = Setting::where('status', 1)->first();

            if (!empty($page)) {
                // Seo Tools
                SEOTools::setTitle($page->page_title);
                SEOTools::setDescription($page->description);

                SEOMeta::setTitle($page->page_title);
                SEOMeta::setDescription($page->description);
                SEOMeta::addMeta('article:section', $page->name, 'property');
                SEOMeta::addKeyword([$page->keywords]);

                OpenGraph::setTitle($page->page_title);
                OpenGraph::setDescription($page->description);
                OpenGraph::setUrl(URL::full());
                OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

                JsonLd::setTitle($page->page_title);
                JsonLd::setDescription($page->description);
                JsonLd::addImage(asset($setting->site_logo));

                // Return values
                $returnValues = compact('page', 'config', 'setting');

                // Check selected theme
                if ($config[48]->config_value == "513402991882314") {
                    // view
                    return view("website.classic.pages.custom-page", $returnValues);
                } else if ($config[48]->config_value == "330599619570398") {
                    // view
                    return view("website.modern.pages.custom-page", $returnValues);
                } else if ($config[48]->config_value == "317109101703740") {
                    // view
                    return view("website.modern-orange.pages.custom-page", $returnValues);
                }
            } else {
                return abort(404);
            }
        } else {
            return redirect('/login');
        }
    }

    // Blogs
    public function blogs()
    {
        // Queries
        $blogs = Blog::where('status', 1)->orderBy('created_at', 'desc')->paginate(6);
        $setting = Setting::where('status', 1)->first();
        $config = Config::get();

        // Get page details
        $page = Page::where('slug', 'home')->where('status', 1)->get();

        // Seo Tools
        SEOTools::setTitle('Blogs');
        SEOTools::setDescription('Blogs' . ' - ' . $page[0]->description);

        SEOMeta::setTitle('Blogs');
        SEOMeta::setDescription('Blogs' . ' - ' . $page[0]->description);
        SEOMeta::addMeta('article:section', 'Blogs', 'property');
        SEOMeta::addKeyword([$page[0]->keywords]);

        OpenGraph::setTitle('Blogs');
        OpenGraph::setDescription('Blogs' . ' - ' . $page[0]->description);
        OpenGraph::setUrl(URL::full());
        OpenGraph::addImage([asset($setting->site_logo), 'size' => 300]);

        JsonLd::setTitle('Blogs');
        JsonLd::setDescription('Blogs' . ' - ' . $page[0]->description);
        JsonLd::addImage(asset($setting->site_logo));

        // Return values
        $returnValues = compact('blogs', 'config', 'setting');

        // Check selected theme
        if ($config[48]->config_value == "513402991882314") {
            // view
            return view("website.classic.pages.blogs.index", $returnValues);
        } else if ($config[48]->config_value == "330599619570398") {
            // view
            return view("website.modern.pages.blogs.index", $returnValues);
        } else if ($config[48]->config_value == "317109101703740") {
            // view
            return view("website.modern-orange.pages.blogs.index", $returnValues);
        }
    }

    // View blog post
    public function viewBlog($slug)
    {
        // Queries
        $blogDetails = Blog::where('slug', $slug)->where('status', 1)->first();
        $setting = Setting::where('status', 1)->first();
        $config = Config::get();

        if ($blogDetails) {
            // Get page details
            $page = Page::where('slug', 'home')->where('status', 1)->get();

            // Recent blogs (except current viewed blog)
            $recentBlogs = Blog::where('slug', '!=', $slug)->where('status', 1)->limit(2)->orderBy('created_at', 'desc')->get();

            // Seo Tools
            SEOTools::setTitle($blogDetails->title);
            SEOTools::setDescription($blogDetails->description);
            SEOTools::addImages(asset($blogDetails->cover_image));

            SEOMeta::setTitle($blogDetails->title);
            SEOMeta::setDescription($blogDetails->description);
            SEOMeta::addMeta('article:section', $blogDetails->title, 'property');
            SEOMeta::addKeyword([$blogDetails->keywords]);

            OpenGraph::setTitle($blogDetails->title);
            OpenGraph::setDescription($blogDetails->description);
            OpenGraph::addProperty('type', 'article');
            OpenGraph::setUrl(url($blogDetails->slug));

            JsonLd::setType('Article');
            JsonLd::setTitle($blogDetails->title);
            JsonLd::setDescription($blogDetails->description);

            // Return values
            $returnValues = compact('blogDetails', 'recentBlogs', 'config', 'setting');

            // Check selected theme
            if ($config[48]->config_value == "513402991882314") {
                // view
                return view("website.classic.pages.blogs.view", $returnValues);
            } else if ($config[48]->config_value == "330599619570398") {
                // view
                return view("website.modern.pages.blogs.view", $returnValues);
            } else if ($config[48]->config_value == "317109101703740") {
                // view
                return view("website.modern-orange.pages.blogs.view", $returnValues);
            }
        } else {
            abort(404);
        }
    }
}
