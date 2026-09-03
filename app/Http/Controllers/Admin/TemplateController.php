<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CustomTemplate;
use App\Http\Controllers\Controller;
use App\Models\CustomTemplateCategory;
use App\Models\CustomTemplateField;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

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
    // public function index()
    // {
    //     // Queries
    //     $templates = CustomTemplate::join('custom_template_categories', 'custom_templates.category_id', '=', 'custom_template_categories.id')->join('custom_template_fields', 'custom_templates.id', '=', 'custom_template_fields.template_id')->select('custom_templates.*', 'custom_template_categories.category_name', 'custom_template_fields.ai_input', 'custom_template_fields.field_type', 'custom_template_fields.field_name', 'custom_template_fields.field_description')->orderBy('custom_templates.id', 'DESC')->groupBy('custom_templates.id')->get();

    //     return view('admin.pages.templates.index', compact('templates'));
    // }



    // All Templates
    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 10);
        $search = $request->input('search');

        $templates = CustomTemplate::join(
            'custom_template_categories',
            'custom_templates.category_id',
            '=',
            'custom_template_categories.id'
        )
            ->join(
                'custom_template_fields',
                'custom_templates.id',
                '=',
                'custom_template_fields.template_id'
            )
            ->select(
                'custom_templates.*',
                'custom_template_categories.category_name',
                'custom_template_fields.ai_input',
                'custom_template_fields.field_type',
                'custom_template_fields.field_name',
                'custom_template_fields.field_description'
            )
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('custom_templates.name', 'like', "%{$search}%")
                        ->orWhere('custom_templates.description', 'like', "%{$search}%")
                        ->orWhere(
                            'custom_template_categories.category_name',
                            'like',
                            "%{$search}%"
                        );
                });
            })
            ->orderBy('custom_templates.id', 'DESC')
            ->groupBy('custom_templates.id')
            ->paginate($perPage)
            ->withQueryString();

        $templates->getCollection()->transform(function ($template) {
            $template->formatted_updated_at = formatDateForUser(
                $template->updated_at
            );

            return $template;
        });

        return Inertia::render('admin/content-templates/templates/index', [
            'templates' => $templates,
            'filters' => [
                'search' => $search,
                'per_page' => $perPage,
            ],
        ]);
    }

    // Add Template
    public function addTemplate()
    {
        $categories = CustomTemplateCategory::where('status', 1)->get();

        return Inertia::render(
            'admin/content-templates/templates/add',
            [
                'categories' => $categories,
            ]
        );
    }

    // Save Template
    public function saveTemplate(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'aiInput.*' => 'required',
            'fieldType.*' => 'required',
            'fieldTitle.*' => 'required',
            'fieldDescription.*' => 'required',
            'prompt' => 'required',
        ], [
            'category_id.required' => 'Category is required.',
            'name.required' => 'Template name is required.',
            'description.required' => 'Description is required.',
            'aiInput.*.required' => 'AI input is required.',
            'fieldType.*.required' => 'Field type is required.',
            'fieldTitle.*.required' => 'Field title is required.',
            'fieldDescription.*.required' => 'Field description is required.',
            'prompt.required' => 'Prompt is required.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

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

        return redirect()->route('dashboard.admin.add.template')->with('success', trans('New Template Created Successfully!'));
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
    // Activate / Deactivate Template
    public function deleteTemplate(Request $request)
    {
        $template = CustomTemplate::find($request->query('id'));

        if (!$template) {
            return back()->withErrors([
                'action' => __('Template not found.'),
            ]);
        }

        $status = $template->status == 0 ? 1 : 0;

        $template->update([
            'status' => $status,
        ]);

        return back()->with(
            'success',
            __('Template Status Updated Successfully!')
        );
    }
}
