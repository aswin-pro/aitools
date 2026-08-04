<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
    public function index()
    {
        // Queries
        $blogsCategories = BlogCategory::where('status', '!=', 2)->get();

        // View
        return view('admin.pages.blogs.categories.index', compact('blogsCategories'));
    }

    // Add Blog Category
    public function createBlogCategory()
    {
        // View
        return view('admin.pages.blogs.categories.create');
    }

    // Create Blog Category
    public function publishBlogCategory(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|min:3',
            'category_slug' => 'required|min:3'
        ]);

        if ($validator->fails()) {
            return back()->with('failed', $validator->messages()->all()[0])->withInput();
        }

        // Save Blog Category
        $blogCategory = new BlogCategory();
        $blogCategory->published_by = Auth::user()->id;
        $blogCategory->blog_category_id = uniqid();
        $blogCategory->blog_category_title = ucfirst($request->category_name);
        $blogCategory->blog_category_slug = $request->category_slug;
        $blogCategory->save();

        // Redirect
        return redirect()->route('admin.create.blog.category')->with('success', trans('Category created successfully!'));
    }

    // Edit Blog Category
    public function editBlogCategory($id)
    {
        // Get page details
        $blogCategoryDetails = BlogCategory::where('blog_category_id', $id)->where('status', 1)->first();

        // View
        return view('admin.pages.blogs.categories.edit', compact('blogCategoryDetails'));
    }

    // Update Blog Category
    public function updateBlogCategory(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|min:3',
            'category_slug' => 'required|min:3'
        ]);

        if ($validator->fails()) {
            return back()->with('failed', $validator->messages()->all()[0])->withInput();
        }

        // Blog category id
        $blogCategoryId = $request->segment(3);
        
        // Update query
        BlogCategory::where('blog_category_id', $blogCategoryId)->update(['blog_category_title' => ucfirst($request->category_name), 'blog_category_slug' => $request->category_slug]);

        // Redirect
        return redirect()->route('admin.edit.blog.category', $blogCategoryId)->with('success', trans('Category details update successfully!'));
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
        return redirect()->route('admin.blog.categories')->with('success', trans('Status updated successfully!'));
    }
}
