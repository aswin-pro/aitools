<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Chat;
use App\Models\Plan;
use App\Models\User;
use App\Models\Config;
use App\Models\Setting;
use App\Classes\AiTools;
use App\Models\Generate;
use Illuminate\Http\Request;
use App\Models\CustomTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomTemplateCategory;

class AIContentCreatorController extends Controller
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

    // Show all AI Contents
    public function indexAllAiContent()
    {
        // Check active plans
        $active_plan = Plan::where('id', Auth::user()->plan_id)->first();

        // Check user plan
        $plan = User::where('id', Auth::user()->id)->first();

        // Check plan
        if ($active_plan != null) {
            // Get User Generated contents
            $contents = Generate::where('generate_by', Auth::user()->id)->where('type', '!=', 'chatgenius')->where('type', '!=', 'Speech to Text')->where('type', '!=', 'Text To Speech')->where('type', '!=', 'Code')->where('status', 1)->orderBy('updated_at', 'desc')->paginate(7);
            $settings = Setting::where('status', 1)->first();

            return view('user.pages.ai-content.index', compact('contents', 'settings'));
        } else {
            // Page redirect in plan is not activated
            return redirect()->route('user.plans');
        }
    }

    // Show all AI Temapltes
    public function indexAiTemplates()
    {
        // Check active plans
        $active_plan = Plan::where('id', Auth::user()->plan_id)->first();

        // Check plan
        if ($active_plan != null) {
            // Get user used word count
            $generateWordCount = Generate::where('generate_by', Auth::user()->id)->sum('word_count');
            $chatWordCount = Chat::where('generate_by', Auth::user()->id)->sum('word_count');

            $usesWordsCount = $generateWordCount + $chatWordCount;

            // Get plan details
            $plan = User::where('id', Auth::user()->id)->first();
            $plan_details = json_decode($plan->plan_details);

            // Check validity
            $validity = User::where('id', Auth::user()->id)->whereDate('plan_validity', '>=', Carbon::now())->count();

            // Check maximum words
            $max_words = $plan_details->max_words;

            // Check user used word count
            if ($validity == 1) {
                if ($usesWordsCount < (int)$max_words) {
                    // Templates categories
                    $templateCategories = CustomTemplateCategory::where('status', 1)->get();

                    // Templates categories
                    for ($i = 0; $i < count($templateCategories); $i++) {
                        $templateCategories[$i]['templates'] = CustomTemplate::join('custom_template_fields', 'custom_templates.id', '=', 'custom_template_fields.template_id')->select('custom_templates.*', 'custom_template_fields.*')->where('custom_templates.status', 1)->where('custom_templates.category_id', $templateCategories[$i]->id)->groupBy('custom_templates.id')->get();
                    }

                    // All templates
                    $allTemplates = CustomTemplate::join('custom_template_fields', 'custom_templates.id', '=', 'custom_template_fields.template_id')->select('custom_templates.*', 'custom_template_fields.*')->where('custom_templates.status', 1)->groupBy('custom_templates.id')->get();

                    return view('user.pages.ai-content.pages.index', compact('allTemplates', 'templateCategories', 'active_plan'));
                } else {
                    // Redirect
                    return redirect()->route('user.all.ai.content')->with('failed', trans('Maximum words limit is exceeded, Please upgrade your plan.'));
                }
            } else {
                // Redirect
                return redirect()->route('user.all.ai.content')->with('failed', trans('Your plan is over. Choose your plan renewal or new package and use it.'));
            }
        } else {
            // Page redirect in plan is not activated
            return redirect()->route('user.plans');
        }
    }

    // New AI Content
    public function indexNewAiContent(Request $request, $templateSlug)
    {
        // Queries
        $config = Config::get();
        // Check active plans
        $active_plan = Plan::where('id', Auth::user()->plan_id)->first();

        // Check plan
        if ($active_plan != null) {
            // Get user used word count
            $generateWordCount = Generate::where('generate_by', Auth::user()->id)->sum('word_count');
            $chatWordCount = Chat::where('generate_by', Auth::user()->id)->sum('word_count');

            $usesWordsCount = $generateWordCount + $chatWordCount;

            // Get plan details
            $plan = User::where('id', Auth::user()->id)->first();
            $plan_details = json_decode($plan->plan_details);

            // Check validity
            $validity = User::where('id', Auth::user()->id)->whereDate('plan_validity', '>=', Carbon::now())->count();

            // Check maximum words
            $max_words = $plan_details->max_words;

            // Check tool
            if ($validity == 1) {
                // Check user used word count
                if ($usesWordsCount < (int)$max_words) {
                    // Template
                    $template_details = CustomTemplate::join('custom_template_fields', 'custom_templates.id', '=', 'custom_template_fields.template_id')->select('custom_templates.*', 'custom_template_fields.*')->where('custom_templates.unique_slug', $templateSlug)->where('custom_templates.status', 1)->get();

                    return view('user.pages.ai-content.pages.generate', compact('template_details', 'config'));
                } else {
                    // Redirect
                    return redirect()->route('user.all.ai.content')->with('failed', trans('Maximum words limit is exceeded, Please upgrade your plan.'));
                }
            } else {
                // Redirect
                return redirect()->route('user.all.ai.content')->with('failed', trans('Your plan is over. Choose your plan renewal or new package and use it.'));
            }
        } else {
            // Page redirect in plan is not activated
            return redirect()->route('user.plans');
        }
    }

    // Generate content
    public function generateAiContent(Request $request)
    {
        // Get user used word count
        $generateWordCount = Generate::where('generate_by', Auth::user()->id)->sum('word_count');
        $chatWordCount = Chat::where('generate_by', Auth::user()->id)->sum('word_count');

        $usesWordsCount = $generateWordCount + $chatWordCount;

        // Get plan details
        $plan = User::where('id', Auth::user()->id)->first();
        $plan_details = json_decode($plan->plan_details);

        // Check maximum words
        $max_words = $plan_details->max_words;

        if ($usesWordsCount < (int)$max_words) {
            // Generate AI Content
            $tool = new AiTools;
            $tool->generate($request);

            // Check result
            if ($tool->result != null) {
                // Parameters
                $level = (float)$request->level;
                $maxTokens = $request->max_length;
                $topP = 1.0;
                $frequencyPenalty = 0.0;
                $presencePenalty = 0.0;

                // Result
                $generateId = random_int(000000000000001, 99999999999999);

                // Settings Parameters
                $settings = [];
                $settings['lang'] = $request->lang;
                $settings['max_length'] = $request->max_length;
                $settings['results'] = $request->results;
                $settings['level'] = $level;
                $settings['max_tokens'] = $maxTokens;
                $settings['top_p'] = $topP;
                $settings['frequency_penalty'] = $frequencyPenalty;
                $settings['presence_penalty'] = $presencePenalty;


                // Save Generate Content
                $saveContent = new Generate();
                $saveContent->generate_id = $generateId;
                $saveContent->generate_by = Auth::user()->id;
                $saveContent->name = $request->input1;
                $saveContent->type = $request->type;
                $saveContent->lang = $request->lang;
                $saveContent->content = $tool->result;
                $saveContent->word_count = $this->utf8_str_word_count($tool->result);
                $saveContent->parameters = json_encode($settings);
                $saveContent->save();

                return response()->json([$tool->result, $generateId], 200);
            } else {
                return response()->json($tool->result, 200);
            }
        } else {
            // Redirect
            return response()->json(404);
        }
    }

    // View generate content
    public function viewAiContent($generateId)
    {
        // Get single content data
        $content = Generate::where('generate_id', $generateId)->where('generate_by', Auth::user()->id)->first();

        // Check content
        if (isset($content)) {
            return view('user.pages.ai-content.view', compact('content'));
        } else {
            return redirect()->route('user.all.ai.content')->with('failed', trans('Wrong content ID.'));
        }
    }

    // Edit generate content
    public function editAiContent($generateId)
    {
        // Get single content data
        $content = Generate::where('generate_id', $generateId)->where('generate_by', Auth::user()->id)->first();
        $config = Config::get();

        // Check content
        if (isset($content)) {
            return view('user.pages.ai-content.edit', compact('content', 'config'));
        } else {
            return redirect()->route('user.all.ai.content')->with('failed', trans('Wrong content ID.'));
        }
    }

    // Update generate content
    public function updateAiContent(Request $request)
    {
        // Update single content data
        Generate::where('generate_id', $request->generateId)->where('generate_by', Auth::user()->id)->update([
            'content' => $request->result
        ]);

        return redirect()->route('user.edit.ai.content', $request->generateId)->with('success', trans('Updated!'));
    }

    // Delete generate content
    public function deleteAiContent(Request $request)
    {
        $content = Generate::where('generate_id', $request->query('id'))->where('generate_by', Auth::user()->id)->first();

        // Check content
        if (isset($content)) {
            // Delete single content data
            Generate::where('generate_id', $request->query('id'))->where('generate_by', Auth::user()->id)->update([
                'status' => 0
            ]);

            return redirect()->route('user.all.ai.content')->with('success', trans('Deleted!'));
        } else {
            return redirect()->route('user.all.ai.content')->with('failed', trans('Wrong content ID.'));
        }
    }

    // Export docs
    public function exportDocsAiContent($generateId)
    {
        // Get single content data
        $content = Generate::where('generate_id', $generateId)->where('generate_by', Auth::user()->id)->first();

        $headers = array(
            'Content-type' => 'text/html',
            'Content-Disposition' => 'attachment; Filename=' . $content->name . '.doc'
        );

        return response()->make(view('user.pages.ai-content.includes.export-doc', compact('content')), 200, $headers);
    }


    // Word count
    function utf8_str_word_count($string, $format = 0, $charlist = null)
    {
        if ($charlist === null) {
            $regex = '/\\pL[\\pL\\p{Mn}\'-]*/u';
        } else {
            $split = array_map(
                'preg_quote',
                preg_split('//u', $charlist, -1, PREG_SPLIT_NO_EMPTY)
            );
            $regex = sprintf(
                '/(\\pL|%1$s)([\\pL\\p{Mn}\'-]|%1$s)*/u',
                implode('|', $split)
            );
        }
        switch ($format) {
            default:
            case 0:
                // For PHP >= 5.4.0 this is fine:
                return preg_match_all($regex, $string);

                // For PHP < 5.4 it's necessary to do this:
                // $results = null;
                // return preg_match_all($regex, $string, $results);
            case 1:
                $results = null;
                preg_match_all($regex, $string, $results);
                return $results[0];
            case 2:
                $results = null;
                preg_match_all($regex, $string, $results, PREG_OFFSET_CAPTURE);
                return empty($results[0])
                    ? array()
                    : array_combine(
                        array_map('end', $results[0]),
                        array_map('reset', $results[0])
                    );
        }
    }
}
