<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class BlogCategoryController extends Controller
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

    // Blogs Category
    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 10);
        $search = $request->input('search');

        $blogsCategories = BlogCategory::query()
            ->where('status', '!=', 2)
            ->when($search, function ($query) use ($search) {
                $query->where('blog_category_title', 'like', "%{$search}%")
                    ->orWhere('blog_category_slug', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('admin/blogs/categories/index', [
            'blogsCategories' => $blogsCategories,
        ]);
    }


    // Create Blog Category
    public function publishBlogCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => [
                'required',
                'string',
                'min:3',
                'max:100',
            ],
            'category_slug' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                'unique:blog_categories,blog_category_slug',
            ],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Save Blog Category
        $blogCategory = new BlogCategory();
        $blogCategory->published_by = Auth::user()->id;
        $blogCategory->blog_category_id = uniqid();
        $blogCategory->blog_category_title = ucfirst($request->category_name);
        $blogCategory->blog_category_slug = $request->category_slug;
        $blogCategory->save();

        // Redirect
        return redirect()->route('dashboard.admin.blog.categories')->with('success', trans('Category created successfully!'));
    }

    public function updateBlogCategory(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => [
                'required',
                'string',
                'min:3',
                'max:100',
            ],
            'category_slug' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('blog_categories', 'blog_category_slug')
                    ->ignore($id, 'blog_category_id'),
            ],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        BlogCategory::where('blog_category_id', $id)->update([
            'blog_category_title' => ucfirst($request->category_name),
            'blog_category_slug' => $request->category_slug,
        ]);

        return redirect()
            ->route('dashboard.admin.blog.categories')
            ->with('success', trans('Category details update successfully!'));
    }

    // Actions
    public function actionBlogCategory(Request $request)
    {
        // Check status
        switch ($request->query('mode')) {
            case 'unpublish':
                $status = 0;
                break;

            case 'delete':
                $status = 2;
                break;

            default:
                $status = 1;
                break;
        }

        // Update status
        BlogCategory::where('blog_category_id', $request->query('id'))->update(['status' => $status]);

        // Redirect
        // return redirect()->route('admin.blog.categories')->with('success', trans('Status updated successfully!'));
    }
}
