<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomTemplateCategory;
use Illuminate\Http\Request;

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
    public function index()
    {
        // Queries
        $categories = CustomTemplateCategory::orderBy('id', 'desc')->get();

        return view('admin.pages.categories.index', compact('categories'));
    }

    // Add Category
    public function addCategory()
    {
        return view('admin.pages.categories.add');
    }

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

        return redirect()->route('admin.add.category')->with('success', trans('New Category Created Successfully!'));
    }

    // Edit Category
    public function editCategory(Request $request, $id)
    {
        // Queries
        $id = $request->id;
        $category_details = CustomTemplateCategory::where('id', $id)->first();

        // Category Checking
        if ($category_details == null) {
            return view('errors.404');
        } else {
            return view('admin.pages.categories.edit', compact('category_details'));
        }
    }

    // Update Category
    public function updateCategory(Request $request)
    {
        // Validation
        $validator = $request->validate([
            'category_id' => 'required',
            'category_name' => 'required'
        ]);

        // Update Category
        CustomTemplateCategory::where('id', $request->category_id)->update([
            'category_name' => ucfirst($request->category_name)
        ]);

        return redirect()->route('admin.edit.category', $request->category_id)->with('success', trans('Category Details Updated Successfully!'));
    }

    // Deactivate Category
    public function deleteCategory(Request $request)
    {
        // Get plan details
        $category_details = CustomTemplateCategory::where('id', $request->query('id'))->first();

        // Check status
        if ($category_details->status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        // Update status
        CustomTemplateCategory::where('id', $request->query('id'))->update(['status' => $status]);
        return redirect()->route('admin.index.categories')->with('success', trans('Category Status Updated Successfully!'));
    }
}
