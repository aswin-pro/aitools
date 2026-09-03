<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\CustomTemplate;
use App\Models\Plan;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display plans.
     */
    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 10);
        $search = $request->input('search');

        $plans = Plan::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        $currencies = Setting::where('status', 1)->get();
        $settings = Setting::where('status', 1)->first();
        $config = Config::get();

        return Inertia::render('admin/plans/index', [
            'plans' => $plans,
            'filters' => [
                'search' => $search,
                'per_page' => $perPage,
            ],
            'currencies' => $currencies,
            'settings' => $settings,
            'config' => $config,
        ]);
    }

    /**
     * Show add plan page.
     */
    public function addPlan()
    {
        $config = Config::get();

        $settings = Setting::where('status', 1)->first();

        $templates = CustomTemplate::where('status', 1)
            ->groupBy('id')
            ->get();

        return Inertia::render('admin/plans/add', [
            'templates' => $templates,
            'settings' => $settings,
            'config' => $config,
        ]);
    }

    /**
     * Save a new plan.
     */
    public function savePlan(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'description' => 'required',
                'price' => 'required|numeric|min:0',
                'validity' => 'required|integer|min:1',

                'ai_credits' => 'required|integer|min:0',
                'ai_image_credits' => 'required|integer|min:0',
            ],
            [
                'name.required' => __('Plan name is required.'),
                'description.required' => __('Plan description is required.'),
                'price.required' => __('Plan price is required.'),
                'price.numeric' => __('Plan price must be a valid number.'),
                'price.min' => __('Plan price cannot be negative.'),
                'validity.required' => __('Plan validity is required.'),
                'validity.integer' => __('Plan validity must be a valid number.'),
                'validity.min' => __('Plan validity must be at least 1 day.'),
                'ai_credits.required' => __('AI credits are required.'),
                'ai_credits.integer' => __('AI credits must be a valid number.'),
                'ai_credits.min' => __('AI credits cannot be negative.'),
                'ai_image_credits.required' => __('AI image credits are required.'),
                'ai_image_credits.integer' => __('AI image credits must be a valid number.'),
                'ai_image_credits.min' => __('AI image credits cannot be negative.'),
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

  
        $templates = CustomTemplate::where('status', 1)
            ->groupBy('id')
            ->get();

        $contentTemplates = [];

        foreach ($templates as $template) {
            $contentTemplates[$template->unique_slug] =
                $request->boolean($template->unique_slug) ? 1 : 0;
        }

        $plan = new Plan();


        $plan->is_private = $request->boolean('is_private');

        $plan->name = ucfirst($request->input('name'));

        $plan->description = $request->input('description');

        $plan->price = $request->input('price');

        $plan->validity = $request->input('validity');

        $plan->content_templates = $contentTemplates;

        $plan->ai_credits = $request->input('ai_credits');

        $plan->ai_image_credits = $request->input('ai_image_credits');

        $plan->speech_to_text = $request->boolean('speech_to_text');

        $plan->text_to_speech = $request->boolean('text_to_speech');

        $plan->code_generator = $request->boolean('code_generator');

        $plan->personalized_chat = $request->boolean('personalized_chat');

        $plan->document_analyzer = $request->boolean('document_analyzer');

        $plan->site_analyzer = $request->boolean('site_analyzer');

        $plan->is_recommended = $request->boolean('is_recommended');

        $plan->customer_support = $request->boolean('customer_support');

        $plan->status = 1;

        $plan->save();

        return redirect()
            ->route('dashboard.admin.add.plan')
            ->with('success', __('Plan added successfully!'));
    }

    /**
     * Show edit plan page.
     */
    public function editPlan(Request $request, $id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            abort(404);
        }

        $config = Config::get();

        $settings = Setting::where('status', 1)->first();

      
        $templates = CustomTemplate::query()
            ->groupBy('id')
            ->get();

        return Inertia::render('admin/plans/edit', [
            'plan' => $plan,
            'templates' => $templates,
            'settings' => $settings,
            'config' => $config,
        ]);
    }

    /**
     * Update an existing plan.
     */
    public function updatePlan(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:plans,id',

                'name' => 'required',
                'description' => 'required',
                'price' => 'required|numeric|min:0',
                'validity' => 'required|integer|min:1',

                'ai_credits' => 'required|integer|min:0',
                'ai_image_credits' => 'required|integer|min:0',
            ],
            [
                'id.required' => __('Plan ID is required.'),
                'id.exists' => __('Plan not found.'),

                'name.required' => __('Plan name is required.'),
                'description.required' => __('Plan description is required.'),
                'price.required' => __('Plan price is required.'),
                'price.numeric' => __('Plan price must be a valid number.'),
                'price.min' => __('Plan price cannot be negative.'),
                'validity.required' => __('Plan validity is required.'),
                'validity.integer' => __('Plan validity must be a valid number.'),
                'validity.min' => __('Plan validity must be at least 1 day.'),
                'ai_credits.required' => __('AI credits are required.'),
                'ai_credits.integer' => __('AI credits must be a valid number.'),
                'ai_credits.min' => __('AI credits cannot be negative.'),
                'ai_image_credits.required' => __('AI image credits are required.'),
                'ai_image_credits.integer' => __('AI image credits must be a valid number.'),
                'ai_image_credits.min' => __('AI image credits cannot be negative.'),
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $plan = Plan::find($request->input('id'));

        if (!$plan) {
            return back()->withErrors([
                'id' => __('Plan not found.'),
            ]);
        }

       
        $templates = CustomTemplate::query()
            ->groupBy('id')
            ->get();

        $contentTemplates = [];

        foreach ($templates as $template) {
            $contentTemplates[$template->unique_slug] =
                $request->boolean($template->unique_slug) ? 1 : 0;
        }

        $plan->is_private = $request->boolean('is_private');

        $plan->name = ucfirst($request->input('name'));

        $plan->description = $request->input('description');

        $plan->price = $request->input('price');

        $plan->validity = $request->input('validity');

        $plan->content_templates = $contentTemplates;

        $plan->ai_credits = $request->input('ai_credits');

        $plan->ai_image_credits = $request->input('ai_image_credits');

        $plan->speech_to_text = $request->boolean('speech_to_text');

        $plan->text_to_speech = $request->boolean('text_to_speech');

        $plan->code_generator = $request->boolean('code_generator');

        $plan->personalized_chat = $request->boolean('personalized_chat');

        $plan->document_analyzer = $request->boolean('document_analyzer');

        $plan->site_analyzer = $request->boolean('site_analyzer');

        $plan->is_recommended = $request->boolean('is_recommended');

        $plan->customer_support = $request->boolean('customer_support');

        $plan->save();

        return redirect()
            ->route('dashboard.admin.edit.plan', $plan->id)
            ->with('success', __('Plan updated successfully!'));
    }

    /**
     * Activate / deactivate a plan.
     */
    public function deletePlan(Request $request)
    {
        $plan = Plan::find($request->query('id'));

        if (!$plan) {
            return back()->withErrors([
                'action' => __('Plan not found.'),
            ]);
        }

        if ($plan->status == 1) {
            $plan->status = 0;

            $message = __('Plan deactivated successfully!');
        } else {
            $plan->status = 1;

            $message = __('Plan activated successfully!');
        }

        $plan->save();

        return back()->with('success', $message);
    }
}