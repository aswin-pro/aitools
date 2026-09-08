<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Models\Config;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
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



    public function index(Request $request)
    {
        $config = Config::get();

        $pages = Page::where('theme_id', $config[48]->config_value)
            ->where('name', '!=', 'Custom Page')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->orderBy('id')
            ->paginate(
                $request->integer('per_page', 10),
                ['*'],
                'page'
            )
            ->withQueryString();

        $custom_pages = Page::where('name', 'Custom Page')
            ->when($request->custom_search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(
                $request->integer('custom_per_page', 10),
                ['*'],
                'custom_page'
            )
            ->withQueryString();

        return Inertia::render('admin/pages/index', [
            'pages' => $pages,
            'custom_pages' => $custom_pages,
        ]);
    }

    // Add page
    public function addPage()
    {
        // Queries
        $config = Config::get();

        return Inertia::render('admin/pages/create-custom-page');
    }

    // Save page
    public function savePage(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:pages,slug'],
            'body' => ['required', 'string'],
            'page_title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'keywords' => ['required', 'string', 'max:255'],
        ]);

        $config = Config::get();

        $page = new Page();

        $page->theme_id = $config[48]->config_value;
        $page->name = 'Custom Page';
        $page->title = $request->title;
        $page->slug = $request->slug;
        $page->body = $request->body;
        $page->page_title = $request->page_title;
        $page->description = $request->description;
        $page->keywords = $request->keywords;
        $page->status = 1;

        $page->save();

        return redirect()
            ->route('dashboard.admin.pages')
            ->with('success', 'Custom page created successfully.');
    }

    // Edit custom page

    public function editCustomPage($id)
    {
        $config = Config::get();

        $page = Page::where('theme_id', $config[48]->config_value)
            ->where('id', $id)
            ->firstOrFail();

        return Inertia::render('admin/pages/edit-custom-page', [
            'page' => $page,
        ]);
    }

    // Edit page


    public function editPage($id)
    {
        $config = Config::get();

        $sections = Page::where('theme_id', $config[48]->config_value)
            ->where('slug', $id)
            ->orderBy('id')
            ->get();

        if ($sections->isEmpty()) {
            abort(404);
        }

        return Inertia::render('admin/pages/editor', [
            'page' => $sections->first(),
            'sections' => $sections,
            'theme' => 'modern-orange',
        ]);
    }



    public function updatePage(Request $request, $id)
    {
        $config = Config::get();

        $page = Page::where('slug', $id)
            ->where('theme_id', $config[48]->config_value)
            ->firstOrFail();

        $validated = $request->validate([
            'body' => ['required', 'string'],
            'page_title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'keywords' => ['required', 'string'],
        ]);

        $page->body = $validated['body'];
        $page->page_title = $validated['page_title'];
        $page->description = $validated['description'];
        $page->keywords = $validated['keywords'];

        $page->save();

        return redirect()
            ->route('dashboard.admin.pages')
            ->with('success', 'Website Content Updated Successfully!');
    }

    // Update custom page
    public function updateCustomPage(Request $request)
    {
        $request->validate([
            'page_id' => ['required', 'exists:pages,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pages', 'slug')->ignore($request->page_id),
            ],
            'body' => ['required', 'string'],
            'page_title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'keywords' => ['required', 'string', 'max:255'],
        ]);

        $page = Page::findOrFail($request->page_id);

        $page->title = $request->title;
        $page->slug = $request->slug;
        $page->body = $request->body;
        $page->page_title = $request->page_title;
        $page->description = $request->description;
        $page->keywords = $request->keywords;

        $page->save();

        return redirect()
            ->route('dashboard.admin.pages')
            ->with('success', 'Custom page updated successfully.');
    }

    // Status Page
    public function statusPage(Request $request)
    {
        // Get plan details
        $page_details = Page::where('id', $request->query('id'))->first();

        // Check status
        if ($page_details->status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        // Update status
        Page::where('id', $request->query('id'))->update(['status' => $status]);
        return redirect()->route('dashboard.admin.pages')->with('success', 'Page Status Updated Successfully!');
    }

    // Disable Page
    public function disablePage(Request $request)
    {
        // Get plan details
        $page_details = Page::where('slug', $request->query('id'))->first();

        // Check status
        if ($page_details->status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        // Update status
        Page::where('slug', $request->query('id'))->update(['status' => $status]);
        return redirect()->route('dashboard.admin.pages')->with('success', 'Page Status Updated Successfully!');
    }

    // Delete Page
    public function deletePage(Request $request)
    {
        // Update status
        Page::where('id', $request->query('id'))->delete();
        return redirect()->route('dashboard.admin.pages')->with('success', 'Page Deleted Successfully!');
    }


    public function uploadPageImage(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,gif,webp,svg',
            ],
        ]);

        $file = $request->file('file');

        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)
            . '_' . now()->format('Ymd_His')
            . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs(
            'pages',
            $filename,
            'public'
        );

        return response()->json([
            'data' => [
                [
                    'src' => asset('storage/' . $path),
                    'name' => $file->getClientOriginalName(),
                ],
            ],
        ]);
    }
}
