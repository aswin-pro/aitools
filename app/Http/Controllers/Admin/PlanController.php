<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use App\Models\Config;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\CustomTemplate;
use App\Http\Controllers\Controller;

class PlanController extends Controller
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

    // All Plans
    public function index()
    {
        // Queries
        $plans = Plan::get();
        $currencies = Setting::where('status', 1)->get();
        $settings = Setting::where('status', 1)->first();
        $config = Config::get();

        return view('admin.pages.plans.index', compact('plans', 'currencies', 'settings', 'config'));
    }

    // Add Plan
    public function addPlan()
    {
        // Queries
        $config = Config::get();
        $settings = Setting::where('status', 1)->first();

        // Templates
        $templates = CustomTemplate::groupBy('id')->get();

        return view('admin.pages.plans.add', compact('settings', 'config', 'templates'));
    }

    // Save Plan 
    public function savePlan(Request $request)
    {
        // Validation
        $validator = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'validity' => 'required',
            'templates' => 'required',
            'words' => 'required',
            'images' => 'required',
        ]);

        // Templates
        $templates = CustomTemplate::groupBy('id')->get();

        // Checked Templates
        $availablePlansTemplates = [];

        // Foreach
        foreach ($templates as $template) {
            $currentTemplate = $template->unique_slug;

            // Check template
            if ($request[$currentTemplate] == "on") {
                $availablePlansTemplates[$template->unique_slug] = 1;
            } else {
                $availablePlansTemplates[$template->unique_slug] = 0;
            }
        }

        // Recommended
        if ($request->recommended == null) {
            $recommended = 0;
        } else {
            $recommended = 1;
        }

        // AI Speech to Text
        if ($request->ai_speech_to_text == null) {
            $ai_speech_to_text = 0;
        } else {
            $ai_speech_to_text = 1;
        }

        // AI Text to Speech
        if ($request->ai_text_to_speech == null) {
            $ai_text_to_speech = 0;
        } else {
            $ai_text_to_speech = 1;
        }

        // AI Code
        if ($request->ai_code == null) {
            $ai_code = 0;
        } else {
            $ai_code = 1;
        }

        // AI ChatGenius
        if ($request->ai_chatgenius == null) {
            $ai_chatgenius = 0;
        } else {
            $ai_chatgenius = 1;
        }

        // AI DocsAssistant
        if ($request->ai_docsassist == null) {
            $ai_docsassist = 0;
        } else {
            $ai_docsassist = 1;
        }

        // AI WebChat
        if ($request->ai_webchat == null) {
            $ai_webchat = 0;
        } else {
            $ai_webchat = 1;
        }

        // Additional Tools
        if ($request->additional_tools == null) {
            $additional_tools = 0;
        } else {
            $additional_tools = 1;
        }

        // Support
        if ($request->support == null) {
            $support = 0;
        } else {
            $support = 1;
        }

        // Recommended
        if ($request->recommended == null) {
            $recommended = 0;
        } else {
            $recommended = 1;
        }

        // Is Private?
        if ($request->is_private == null) {
            $is_private = 0;
        } else {
            $is_private = 1;
        }

        // Save plan
        $plan = new Plan;
        $plan->is_private = $is_private;
        $plan->plan_id = $request->product_id;
        $plan->name = ucfirst($request->name);
        $plan->description = ucfirst($request->description);
        $plan->price = $request->price;
        $plan->validity = $request->validity;
        $plan->template_counts = $request->templates;
        $plan->templates = json_encode($availablePlansTemplates);
        $plan->max_words = $request->words;
        $plan->max_images = $request->images;
        $plan->ai_speech_to_text = $ai_speech_to_text;
        $plan->ai_text_to_speech = $ai_text_to_speech;
        $plan->ai_code = $ai_code;
        $plan->ai_chatgenius = $ai_chatgenius;
        $plan->ai_docsassist = $ai_docsassist;
        $plan->ai_webchat = $ai_webchat;
        $plan->additional_tools = $additional_tools;
        $plan->recommended = $recommended;
        $plan->support = $support;
        $plan->save();

        return redirect()->route('admin.add.plan')->with('success', trans('New Plan Created Successfully!'));
    }

    // Edit Plan
    public function editPlan(Request $request, $id)
    {
        // Queries
        $id = $request->id;
        $plan_details = Plan::where('id', $id)->first();
        $settings = Setting::where('status', 1)->first();
        $config = Config::get();

        // Plan Checking
        if ($plan_details == null) {
            return view('errors.404');
        } else {
            // Available templates in single plan
            $templates = CustomTemplate::groupBy('id')->get();
            $availableTemplates = json_decode($plan_details->templates, true);
            return view('admin.pages.plans.edit', compact('plan_details', 'templates', 'availableTemplates', 'settings', 'config'));
        }
    }

    // Update Plan
    public function updatePlan(Request $request)
    {
        // Validation
        $validator = $request->validate([
            'plan_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'validity' => 'required',
            'templates' => 'required',
            'words' => 'required',
            'images' => 'required'
        ]);

        // Templates
        $templates = CustomTemplate::groupBy('id')->get();

        // Checked Templates
        $availablePlansTemplates = [];

        // Foreach
        foreach ($templates as $template) {
            $currentTemplate = $template->unique_slug;

            // Check template
            if ($request[$currentTemplate] == "on") {
                $availablePlansTemplates[$template->unique_slug] = 1;
            } else {
                $availablePlansTemplates[$template->unique_slug] = 0;
            }
        }

        // Recommended
        if ($request->recommended == null) {
            $recommended = 0;
        } else {
            $recommended = 1;
        }

        // AI Speech to Text
        if ($request->ai_speech_to_text == null) {
            $ai_speech_to_text = 0;
        } else {
            $ai_speech_to_text = 1;
        }

        // AI Text to Speech
        if ($request->ai_text_to_speech == null) {
            $ai_text_to_speech = 0;
        } else {
            $ai_text_to_speech = 1;
        }

        // AI Code
        if ($request->ai_code == null) {
            $ai_code = 0;
        } else {
            $ai_code = 1;
        }

        // AI ChatGenius
        if ($request->ai_chatgenius == null) {
            $ai_chatgenius = 0;
        } else {
            $ai_chatgenius = 1;
        }

        // AI DocsAssistant
        if ($request->ai_docsassist == null) {
            $ai_docsassist = 0;
        } else {
            $ai_docsassist = 1;
        }

        // AI WebChat
        if ($request->ai_webchat == null) {
            $ai_webchat = 0;
        } else {
            $ai_webchat = 1;
        }

        // Additional Tools
        if ($request->additional_tools == null) {
            $additional_tools = 0;
        } else {
            $additional_tools = 1;
        }

        // Support
        if ($request->support == null) {
            $support = 0;
        } else {
            $support = 1;
        }

        // Recommended
        if ($request->recommended == null) {
            $recommended = 0;
        } else {
            $recommended = 1;
        }

        // Is Private?
        if ($request->is_private == null) {
            $is_private = 0;
        } else {
            $is_private = 1;
        }

        // Update plan
        Plan::where('id', $request->plan_id)->update([
            'is_private' => $is_private, 'plan_id' => $request->product_id, 'name' => ucfirst($request->name), 'description' => ucfirst($request->description),
            'price' => $request->price, 'validity' => $request->validity, 'template_counts' => $request->templates,
            'templates' => json_encode($availablePlansTemplates), 'max_words' => $request->words, 'max_images' => $request->images, 'ai_speech_to_text' => $ai_speech_to_text, 'ai_text_to_speech' => $ai_text_to_speech, 'ai_code' => $ai_code,
            'ai_chatgenius' => $ai_chatgenius, 'ai_docsassist' => $ai_docsassist, 'ai_webchat' => $ai_webchat, 'additional_tools' => $additional_tools, 'recommended' => $recommended, 'support' => $support
        ]);

        return redirect()->route('admin.edit.plan', $request->plan_id)->with('success', trans('Plan Details Updated Successfully!'));
    }

    // Delete Plan
    public function deletePlan(Request $request)
    {
        // Get plan details
        $plan_details = Plan::where('id', $request->query('id'))->first();

        // Check status
        if ($plan_details->status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        // Update status
        Plan::where('id', $request->query('id'))->update(['status' => $status]);
        return redirect()->route('admin.index.plans')->with('success', trans('Plan Status Updated Successfully!'));
    }
}
