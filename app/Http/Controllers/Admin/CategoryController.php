<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomTemplate;
use App\Models\CustomTemplateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class CategoryController extends Controller
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


    // All Categories
    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 10);
        $search = $request->input('search');

        $categories = CustomTemplateCategory::query()
            ->when($search, function ($query) use ($search) {
                $query->where('category_name', 'like', "%{$search}%");
            })
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('admin/content-templates/categories/index', [
            'categories' => $categories,
            'filters' => [
                'search' => $search,
                'per_page' => $perPage,
            ],
        ]);
    }


    // Add Category
    // public function addCategory()
    // {
    //     return view('admin.pages.categories.add');
    // }

    // Save Category
    public function saveCategory(Request $request)
    {
        // Validation
        $validator = $request->validate([
            'category_name' => 'required'
        ]);

        // Save Category
        $category = new CustomTemplateCategory();
        $category->category_name = ucfirst($request->category_name);
        $category->save();

        return back()->with(
            'success',
            __('New Category Created Successfully!')
        );
    }

    // // Edit Category
    // public function editCategory(Request $request, $id)
    // {
    //     // Queries
    //     $id = $request->id;
    //     $category_details = CustomTemplateCategory::where('id', $id)->first();

    //     // Category Checking
    //     if ($category_details == null) {
    //         return view('errors.404');
    //     } else {
    //         return view('admin.pages.categories.edit', compact('category_details'));
    //     }
    // }

    // Update Category
    public function updateCategory(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:custom_template_categories,id',
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        CustomTemplateCategory::where('id', $request->category_id)->update([
            'category_name' => ucfirst($request->category_name),
        ]);

        return back()->with(
            'success',
            __('Category Details Updated Successfully!')
        );
    }

    public function deleteCategory(Request $request)
    {
        $category = CustomTemplateCategory::find($request->query('id'));

        if (!$category) {
            return back()->withErrors([
                'action' => __('Category not found.'),
            ]);
        }

        $action = $request->query('action');

        if ($action === 'active') {
            DB::table('custom_template_categories')
                ->where('id', $category->id)
                ->update([
                    'status' => 1,
                ]);

            return back()->with(
                'success',
                __('Category activated successfully!')
            );
        }

        if ($action === 'inactive') {
            $templateExists = DB::table('custom_templates')
                ->where('category_id', $category->id)
                ->exists();

            if ($templateExists) {
                return back()->withErrors([
                    'action' => __(
                        'This category cannot be deactivated because it is being used by a template.'
                    ),
                ]);
            }

            DB::table('custom_template_categories')
                ->where('id', $category->id)
                ->update([
                    'status' => 0,
                ]);

            return back()->with(
                'success',
                __('Category deactivated successfully!')
            );
        }

        if ($action === 'delete') {
            $templateExists = DB::table('custom_templates')
                ->where('category_id', $category->id)
                ->exists();

            if ($templateExists) {
                return back()->withErrors([
                    'action' => __(
                        'This category cannot be deleted because it is being used by a template.'
                    ),
                ]);
            }

            DB::table('custom_template_categories')
                ->where('id', $category->id)
                ->delete();

            return back()->with(
                'success',
                __('Category deleted successfully!')
            );



            return back()->withErrors([
                'action' => __('Invalid category action.'),
            ]);
        };
    }
}
