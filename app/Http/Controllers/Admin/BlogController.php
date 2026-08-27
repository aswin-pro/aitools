<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PublishBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Config;
use Illuminate\Support\Str;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class BlogController extends Controller
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

    // Check slug exists
    public function createSlug($title, $count = 0)
    {
        // Generate the initial slug from the title
        $slug = Str::slug($title);

        // If a count is provided, append it to the slug
        if ($count > 0) {
            $slug .= '-' . $count;
        }

        // Check if the slug already exists in the database
        $existingSlug = Blog::where('slug', $slug)->first();

        // If the slug exists, recursively call this method with an incremented count
        if ($existingSlug) {
            return $this->createSlug($title, $count + 1);
        }

        // If the slug does not exist, return it
        return $slug;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // Blogs
    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 10);
        $search = $request->input('search');

        $query = Blog::with('blogCategory')
            ->where('status', '!=', 2)
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('heading', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('tags', 'like', "%{$search}%")
                    ->orWhereHas('blogCategory', function ($categoryQuery) use ($search) {
                        $categoryQuery->where(
                            'blog_category_title',
                            'like',
                            "%{$search}%"
                        );
                    });
            });
        }

        $blogs = $query->paginate($perPage)->withQueryString();

        return Inertia::render(
            'admin/blogs/blog-posts/index',
            compact('blogs')
        );
    }

    // Add Blog
    public function createBlog()
    {
        $blogsCategories = BlogCategory::where('status', '!=', 2)->get();

        return Inertia::render(
            'admin/blogs/blog-posts/create',
            compact('blogsCategories')
        );
    }

    // Publish Blog
    public function publishBlog(PublishBlogRequest $request)
    {
        $blogCoverImage = $request->file('blog_cover');

        $fileName = pathinfo(
            $blogCoverImage->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        $extension = $blogCoverImage->getClientOriginalExtension();

        $coverImage = 'images/blogs/cover-images/'
            . $fileName
            . '_'
            . uniqid()
            . '.'
            . $extension;

        $blogCoverImage->move(
            public_path('images/blogs/cover-images'),
            $coverImage
        );

        $blog = new Blog();

        $blog->published_by = Auth::id();
        $blog->blog_id = uniqid();

        $blog->cover_image = $coverImage;
        $blog->heading = ucfirst($request->blog_name);
        $blog->slug = $request->blog_slug;
        $blog->short_description = ucfirst($request->short_description);
        $blog->long_description = $request->long_description;
        $blog->category = $request->category_id;
        $blog->tags = ucfirst($request->tags);

        $blog->title = ucfirst($request->seo_title);
        $blog->description = ucfirst($request->seo_description);
        $blog->keywords = $request->seo_keywords;

        $blog->save();

        return redirect()
            ->route('dashboard.admin.blogs.post')
            ->with(
                'success',
                trans('Blog published successfully!')
            );
    }
    // Edit Blog
    public function editBlog($id)
    {
        $categories = BlogCategory::where('status', '!=', 2)->get();

        $blog = Blog::where('blog_id', $id)
            ->where('status', '!=', 2)
            ->firstOrFail();

        return Inertia::render(
            'admin/blogs/blog-posts/edit',
            [
                'categories' => $categories,
                'blog' => $blog,
            ]
        );
    }

    // Update Blog
    public function updateBlog(UpdateBlogRequest $request, $id)
    {
        $blog = Blog::where('blog_id', $id)
            ->where('status', '!=', 2)
            ->first();

        if (!$blog) {
            return back()->with(
                'failed',
                trans('Blog not found!')
            );
        }


        if ($request->hasFile('blog_cover')) {
            $blogCoverImage = $request->file('blog_cover');

            $fileName = pathinfo(
                $blogCoverImage->getClientOriginalName(),
                PATHINFO_FILENAME
            );

            $extension = $blogCoverImage->getClientOriginalExtension();

            $coverImage = 'images/blogs/cover-images/'
                . $fileName
                . '_'
                . uniqid()
                . '.'
                . $extension;

            $blogCoverImage->move(
                public_path('images/blogs/cover-images'),
                $coverImage
            );

            $blog->cover_image = $coverImage;
        }



        $blog->heading = ucfirst($request->blog_name);
        $blog->slug = $request->blog_slug;
        $blog->short_description = ucfirst(
            $request->short_description
        );
        $blog->long_description = $request->long_description;
        $blog->category = $request->category_id;
        $blog->tags = ucfirst($request->tags);


        $blog->title = ucfirst($request->seo_title);
        $blog->description = ucfirst(
            $request->seo_description
        );
        $blog->keywords = $request->seo_keywords;

        $blog->save();

        return redirect()
            ->route('dashboard.admin.blogs.post')
            ->with(
                'success',
                trans('Blog updated successfully!')
            );
    }

    // Actions
    public function actionBlog(Request $request)
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
        Blog::where('blog_id', $request->query('id'))->update(['status' => $status]);

        // Redirect
        return redirect()
            ->route('dashboard.admin.blogs.post')
            ->with('success', trans('Status updated successfully!'));
    }
}
