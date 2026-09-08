<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContentTemplate;
use App\Models\ContentTemplateCategory;
use App\Models\ContentTemplateField;

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
    // public function index(Request $request)
    // {
    //     $perPage = $request->integer('per_page', 10);
    //     $search = $request->input('search');

    //     $templates = ContentTemplate::join(
    //         'content_template_categories',
    //         'content_templates.category_id',
    //         '=',
    //         'content_template_categories.id'
    //     )
    //         ->join(
    //             'content_template_fields',
    //             'content_template.id',
    //             '=',
    //             'content_template_fields.template_id'
    //         )
    //         ->select(
    //             'content_templates.*',
    //             'content_template_categories.category_name',
    //             'content_template_fields.ai_input',
    //             'content_template_fields.field_type',
    //             'content_template_fields.field_name',
    //             'content_template_fields.field_description'
    //         )
    //         ->when($search, function ($query) use ($search) {
    //             $query->where(function ($query) use ($search) {
    //                 $query->where('content_templates.name', 'like', "%{$search}%")
    //                     ->orWhere('content_templates.description', 'like', "%{$search}%")
    //                     ->orWhere(
    //                         'content_template_categories.category_name',
    //                         'like',
    //                         "%{$search}%"
    //                     );
    //             });
    //         })
    //         ->orderBy('content_templates.id', 'DESC')
    //         ->groupBy('content_templates.id')
    //         ->paginate($perPage)
    //         ->withQueryString();

    //     $templates->getCollection()->transform(function ($template) {
    //         $template->formatted_updated_at = formatDateForUser(
    //             $template->updated_at
    //         );

    //         return $template;
    //     });

    //     return Inertia::render('admin/content-templates/templates/index', [
    //         'templates' => $templates,
    //         'filters' => [
    //             'search' => $search,
    //             'per_page' => $perPage,
    //         ],
    //     ]);
    // }

    public function index(Request $request)
{
    $perPage = $request->integer('per_page', 10);
    $search = $request->input('search');

    $templates = ContentTemplate::join(
        'content_template_categories',
        'content_templates.category_id',
        '=',
        'content_template_categories.id'
    )
        ->join(
            'content_template_fields',
            'content_templates.id',
            '=',
            'content_template_fields.template_id'
        )
        ->select(
            'content_templates.*',
            'content_template_categories.category_name',
            'content_template_fields.ai_input',
            'content_template_fields.field_type',
            'content_template_fields.field_name',
            'content_template_fields.field_description'
        )
        ->when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where(
                    'content_templates.name',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'content_templates.description',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'content_template_categories.category_name',
                        'like',
                        "%{$search}%"
                    );
            });
        })
        ->orderBy('content_templates.id', 'DESC')
        ->groupBy('content_templates.id')
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
        $categories = ContentTemplateCategory::where('status', 1)->get();

        return Inertia::render(
            'admin/content-templates/templates/create',
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
        $template = new ContentTemplate();
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
                $field = new ContentTemplateCategory();
                $field->template_id = $template->id;
                $field->ai_input = $request->aiInput[$i];
                $field->field_type = $request->fieldType[$i];
                $field->field_name = ucfirst($request->fieldTitle[$i]);
                $field->field_description = ucfirst($request->fieldDescription[$i]);
                $field->save();
            } else {
                return redirect()->route('dashboard.admin.add.template')->with('failed', trans('New Template Created Failed!'));
            }
        }

        return redirect()->route('dashboard.admin.add.template')->with('success', trans('New Template Created Successfully!'));
    }

    // Edit Template
    public function editTemplate(Request $request, $id)
    {
        $template = ContentTemplate::find($id);

        if (!$template) {
            abort(404);
        }

        $categories = ContentTemplateCategory::where('status', 1)->get();

        $fields = ContentTemplateField::where('template_id', $template->id)
            ->orderBy('id')
            ->get();

        return Inertia::render('admin/content-templates/templates/edit', [
            'template' => $template,
            'fields' => $fields,
            'categories' => $categories,
        ]);
    }

    // Update Template

    public function updateTemplate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'template_id' => 'required',
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

        $template = ContentTemplate::find($request->template_id);

        if (!$template) {
            return back()->withErrors([
                'template_id' => 'Template not found.',
            ]);
        }

        ContentTemplate::where('id', $request->template_id)->update([
            'category_id' => $request->category_id,
            'name' => ucfirst($request->name),
            'description' => ucfirst($request->description),
            'prompt' => $request->prompt,
        ]);


        ContentTemplateField::where(
            'template_id',
            $request->template_id
        )->delete();

        for (
            $i = 0;
            $i < count($request->fieldTitle);
            $i++
        ) {
            if (
                isset($request->aiInput[$i]) &&
                isset($request->fieldType[$i]) &&
                isset($request->fieldTitle[$i]) &&
                isset($request->fieldDescription[$i])
            ) {
                $field = new ContentTemplateField();

                $field->template_id =
                    $request->template_id;

                $field->ai_input =
                    $request->aiInput[$i];

                $field->field_type =
                    $request->fieldType[$i];

                $field->field_name =
                    ucfirst($request->fieldTitle[$i]);

                $field->field_description =
                    ucfirst(
                        $request->fieldDescription[$i]
                    );

                $field->save();
            }
        }

        return redirect()
            ->route(
                'dashboard.admin.templates'
            )
            ->with(
                'success',
                
                    'Template Details Updated Successfully!'
                
            );
    }

    // Deactivate Template
    // Activate / Deactivate Template
    public function deleteTemplate(Request $request)
    {
        $template = ContentTemplate::find($request->query('id'));

        if (!$template) {
            return back()->withErrors([
                'action' => __('Template not found.')
            ]);
        }

        $status = $template->status == 0 ? 1 : 0;

        $template->update([
            'status' => $status
        ]);

        return back()->with('success', 'Template status updated successfully!');
    }
}
