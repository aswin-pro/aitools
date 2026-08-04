<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CustomTemplate;
use App\Http\Controllers\Controller;
use App\Models\CustomTemplateCategory;
use App\Models\CustomTemplateField;

class TemplateController extends Controller
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

    // All Templates
    public function index()
    {
        // Queries
        $templates = CustomTemplate::join('custom_template_categories', 'custom_templates.category_id', '=', 'custom_template_categories.id')->join('custom_template_fields', 'custom_templates.id', '=', 'custom_template_fields.template_id')->select('custom_templates.*', 'custom_template_categories.category_name', 'custom_template_fields.ai_input', 'custom_template_fields.field_type', 'custom_template_fields.field_name', 'custom_template_fields.field_description')->orderBy('custom_templates.id', 'DESC')->groupBy('custom_templates.id')->get();

        return view('admin.pages.templates.index', compact('templates'));
    }

    // Add Template
    public function addTemplate()
    {
        // Queries
        $categories = CustomTemplateCategory::where('status', 1)->get();

        return view('admin.pages.templates.add', compact('categories'));
    }

    // Save Template
    public function saveTemplate(Request $request)
    {
        // Validation
        $validator = $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'aiInput*' => 'required',
            'fieldType*' => 'required',
            'fieldTitle*' => 'required',
            'fieldDescription*' => 'required',
            'prompt' => 'required'
        ]);

        // Save Template
        $template = new CustomTemplate();
        $template->category_id = $request->category_id;
        $template->unique_slug = Strtolower(str_replace(' ', '_', $request->name));
        $template->name = ucfirst($request->name);
        $template->description = ucfirst($request->description);
        $template->prompt = $request->prompt;
        $template->save();

        // Custom  Template Field
        for ($i = 0; $i < count($request->fieldTitle); $i++) {
            if (isset($request->fieldType[$i]) && isset($request->fieldTitle[$i]) && isset($request->fieldDescription[$i])) {
                // Save Template Field
                $field = new CustomTemplateField();
                $field->template_id = $template->id;
                $field->ai_input = $request->aiInput[$i];
                $field->field_type = $request->fieldType[$i];
                $field->field_name = ucfirst($request->fieldTitle[$i]);
                $field->field_description = ucfirst($request->fieldDescription[$i]);
                $field->save();
            } else {
                return redirect()->route('admin.add.template')->with('failed', trans('New Template Created Failed!'));
            }
        }

        return redirect()->route('admin.add.template')->with('success', trans('New Template Created Successfully!'));
    }

    // Edit Template
    public function editTemplate(Request $request, $id)
    {
        // Queries
        $id = $request->id;
        $categories = CustomTemplateCategory::where('status', 1)->get();
        $template_details = CustomTemplate::join('custom_template_categories', 'custom_templates.category_id', '=', 'custom_template_categories.id')->join('custom_template_fields', 'custom_templates.id', '=', 'custom_template_fields.template_id')->select('custom_templates.*', 'custom_template_categories.category_name', 'custom_template_fields.ai_input', 'custom_template_fields.field_type', 'custom_template_fields.field_name', 'custom_template_fields.field_description')->where('custom_templates.id', $id)->get();

        // Template Checking
        if ($template_details == null) {
            return view('errors.404');
        } else {
            return view('admin.pages.templates.edit', compact('categories', 'template_details'));
        }
    }

    // Update Template
    public function updateTemplate(Request $request)
    {
        // Validation
        $validator = $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'aiInput*' => 'required',
            'fieldType*' => 'required',
            'fieldTitle*' => 'required',
            'fieldDescription*' => 'required',
            'prompt' => 'required'
        ]);

        // Update Custom Template
        CustomTemplate::where('id', $request->template_id)->update(['category_id' => $request->category_id, 'name' => ucfirst($request->name), 'description' => ucfirst($request->description), 'prompt' => $request->prompt]);

        // Delete Custom Template Field (Previous)
        CustomTemplateField::where('template_id', $request->template_id)->delete();

        // Update Custom Template Field
        for ($i = 0; $i < count($request->fieldTitle); $i++) {
            if (isset($request->aiInput[$i]) && isset($request->fieldType[$i]) && isset($request->fieldTitle[$i]) && isset($request->fieldDescription[$i])) {
                // Save Template Field
                $field = new CustomTemplateField();
                $field->template_id = $request->template_id;
                $field->ai_input = $request->aiInput[$i];
                $field->field_type = $request->fieldType[$i];
                $field->field_name = ucfirst($request->fieldTitle[$i]);
                $field->field_description = ucfirst($request->fieldDescription[$i]);
                $field->save();
            } else {
                return redirect()->route('admin.add.template')->with('failed', trans('New Template Created Failed!'));
            }
        }

        return redirect()->route('admin.edit.template', $request->template_id)->with('success', trans('Template Details Updated Successfully!'));
    }

    // Deactivate Template
    public function deleteTemplate(Request $request)
    {
        // Get template details
        $template_details = CustomTemplate::where('id', $request->query('id'))->first();

        // Check status
        if ($template_details->status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        // Update status
        CustomTemplate::where('id', $request->query('id'))->update(['status' => $status]);

        return redirect()->route('admin.categories')->with('success', trans('Template Status Updated Successfully!'));
    }
}
