<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Chat;
use App\Models\Plan;
use App\Models\User;
use App\Models\Config;
use App\Models\Setting;
use App\Models\Generate;
use Illuminate\Http\Request;
use App\Classes\AiTextSpeech;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AiTextToSpeechController extends Controller
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

    // Show all AI Text To Speech
    public function indexAllAiTextToSpeech()
    {
        // Check active plans
        $active_plan = Plan::where('id', Auth::user()->plan_id)->first();

        // Check user plan
        $plan = User::where('id', Auth::user()->id)->first();
        $plan_details = json_decode($plan->plan_details);

        // Check plan
        if ($active_plan != null) {
            // Check premium plan
            if ($plan_details->ai_text_to_speech == 1) {
                // Get User Generated Text To Speech
                $textToSpeeches = Generate::where('generate_by', Auth::user()->id)->where('type', 'Text To Speech')->where('status', 1)->orderBy('updated_at', 'desc')->paginate(7);
                $settings = Setting::where('status', 1)->first();

                return view('user.pages.ai-text.index', compact('textToSpeeches', 'settings'));
            } else {
                // Page redirect in plan is premium plan
                return redirect()->route('user.plans')->with('failed', trans('This is a premium tool. If you want to access this tool, you need a premium plan.'));
            }
        } else {
            // Page redirect in plan is not activated
            return redirect()->route('user.plans');
        }
    }

    // Show all AI Text To Speech
    public function indexNewAiTextToSpeech()
    {
        // Queries
        $config = Config::get();

        // Check active plans
        $active_plan = Plan::where('id', Auth::user()->plan_id)->first();

        // Check plan
        if ($active_plan != null) {

            // Get user used Text To Speech
            $generateWordCount = Generate::where('generate_by', Auth::user()->id)->sum('word_count');
            $chatWordCount = Chat::where('generate_by', Auth::user()->id)->sum('word_count');

            $textToSpeechCount = $generateWordCount + $chatWordCount;

            // Get plan details
            $plan = User::where('id', Auth::user()->id)->first();
            $plan_details = json_decode($plan->plan_details);

            // Check validity
            $validity = User::where('id', Auth::user()->id)->whereDate('plan_validity', '>=', Carbon::now())->count();

            // Check maximum words
            $max_words = $plan_details->max_words;

            // Check user used Text To Speech count
            if ($validity == 1) {
                if ($textToSpeechCount < (int)$max_words) {
                    // Check premium plan
                    if ($plan_details->ai_speech_to_text == 1) {
                        return view('user.pages.ai-text.pages.index', compact('config', 'active_plan'));
                    } else {
                        // Page redirect in plan is premium plan
                        return redirect()->route('user.plans')->with('failed', trans('This is a premium tool. If you want to access this tool, you need a premium plan.'));
                    }
                } else {
                    // Redirect
                    return redirect()->route('user.all.ai.text.to.speech')->with('failed', trans('Maximum Text To Speech limit is exceeded, Please upgrade your plan.'));
                }
            } else {
                // Redirect
                return redirect()->route('user.all.ai.text.to.speech')->with('failed', trans('Your plan is over. Choose your plan renewal or new package and use it.'));
            }
        } else {
            // Page redirect in plan is not activated
            return redirect()->route('user.plans');
        }
    }

    // Generate Text To Speech
    public function generateAiTextToSpeech(Request $request)
    {
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

        // Check validity
        if ($validity == 1) {
            if ($usesWordsCount < (int)$max_words) {
                // Generate AI Content
                $tool = new AiTextSpeech;
                $tool->generate($request);

                // Check result
                if ($tool->result != null) {
                    // Result
                    $generateId = random_int(000000000000001, 99999999999999);

                    // Save Generate Content
                    $saveContent = new Generate();
                    $saveContent->generate_id = $generateId;
                    $saveContent->generate_by = Auth::user()->id;
                    $saveContent->name = $request->name;
                    $saveContent->type = 'Text To Speech'; 
                    $saveContent->lang = "en";
                    $saveContent->content = $tool->result;
                    $saveContent->word_count = $this->utf8_str_word_count($request->content);
                    $saveContent->parameters = "";
                    $saveContent->save();

                    return response()->json([$request->name, $tool->result, $request->audio_format], 200);
                } else {
                    return response()->json($tool->result, 200);
                }
            } else {
                // Redirect
                return response()->json(404);
            }
        } else {
            // Redirect
            return redirect()->route('user.all.ai.text.to.speech')->with('failed', trans('Your plan is over. Choose your plan renewal or new package and use it.'));
        }
    }

    // Delete generate content
    public function deleteAiTextToSpeech(Request $request)
    {
        $content = Generate::where('generate_id', $request->query('id'))->where('generate_by', Auth::user()->id)->first();

        // Check content
        if (isset($content)) {
            // Delete single content data
            Generate::where('generate_id', $request->query('id'))->where('generate_by', Auth::user()->id)->update([
                'status' => 0
            ]);

            return redirect()->route('user.all.ai.text.to.speech')->with('success', trans('Deleted!'));
        } else {
            return redirect()->route('user.all.ai.text.to.speech')->with('failed', trans('Wrong content ID.'));
        }
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