<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Models\Config;
use App\Models\Setting;
use App\Models\AiImages;
use Illuminate\Http\Request;
use App\Classes\AiImages as AiImage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AiImageCreatorController extends Controller
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

    // Show all AI Images
    public function indexAllAiImage()
    {
        // Check active plans
        $active_plan = Plan::where('id', Auth::user()->plan_id)->first();

        // Check user plan
        $plan = User::where('id', Auth::user()->id)->first();

        // Check plan
        if ($active_plan != null) {
            // Get User Generated images
            $images = AiImages::where('generate_by', Auth::user()->id)->where('status', 1)->orderBy('updated_at', 'desc')->paginate(7);
            $settings = Setting::where('status', 1)->first();

            return view('user.pages.ai-images.index', compact('images', 'settings'));
        } else {
            // Page redirect in plan is not activated
            return redirect()->route('user.plans');
        }
    }

    // Show all AI Images
    public function indexNewAiImage()
    {
        // Queries
        $config = Config::get();

        // Check active plans
        $active_plan = Plan::where('id', Auth::user()->plan_id)->first();

        // Check plan
        if ($active_plan != null) {

            // Get user used images
            $usesImagesCount = AiImages::where('generate_by', Auth::user()->id)->sum('n');

            // Get plan details
            $plan = User::where('id', Auth::user()->id)->first();
            $plan_details = json_decode($plan->plan_details);

            // Check validity
            $validity = User::where('id', Auth::user()->id)->whereDate('plan_validity', '>=', Carbon::now())->count();

            // Check maximum words
            $max_images = $plan_details->max_images;

            // Check user used images count
            if ($validity == 1) {
                if ($usesImagesCount < (int)$max_images) {
                    // Check plan
                    if ($active_plan != null) {
                        return view('user.pages.ai-images.pages.index', compact('config', 'active_plan'));
                    } else {
                        // Page redirect in plan is not activated
                        return redirect()->route('user.plans');
                    }
                } else {
                    // Redirect
                    return redirect()->route('user.all.ai.images')->with('failed', trans('Maximum image limit is exceeded, Please upgrade your plan.'));
                }
            } else {
                // Redirect
                return redirect()->route('user.all.ai.images')->with('failed', trans('Your plan is over. Choose your plan renewal or new package and use it.'));
            }
        } else {
            // Page redirect in plan is not activated
            return redirect()->route('user.plans');
        }
    }

    // Generate image
    public function generateAiImage(Request $request)
    {
        // Get user used images
        $usesImagesCount = AiImages::where('generate_by', Auth::user()->id)->sum('n');

        // Get plan details
        $plan = User::where('id', Auth::user()->id)->first();
        $plan_details = json_decode($plan->plan_details);

        // Check validity
        $validity = User::where('id', Auth::user()->id)->whereDate('plan_validity', '>=', Carbon::now())->count();

        // Check maximum words
        $max_images = $plan_details->max_images;

        // Check user used images count
        if ($validity == 1) {
            if ($usesImagesCount < (int)$max_images) {
                // Generate AI Content
                $tool = new AiImage;
                $tool->generate($request);

                if (is_array($tool->result)) {
                    // Result
                    $type = "ai-image";
                    $format = "url";
                    $generateId = random_int(000000000000001, 99999999999999);

                    // Save Generate Image
                    $saveContent = new AiImages();
                    $saveContent->generate_id = $generateId;
                    $saveContent->generate_by = Auth::user()->id;
                    $saveContent->name = $request->name;
                    $saveContent->type = $request->style;
                    $saveContent->prompt = $request->name;
                    $saveContent->n = (int)$request->results;
                    $saveContent->size = $request->size;
                    $saveContent->format = $format;
                    $saveContent->result = json_encode($tool->result);
                    $saveContent->save();
                }

                return response()->json($tool->result, 200);
            } else {
                // Redirect
                return response()->json(404);
            }
        } else {
            // Redirect
            return redirect()->route('user.all.ai.images')->with('failed', trans('Your plan is over. Choose your plan renewal or new package and use it.'));
        }
    }

    // View generate image
    public function viewAiImage($generateId)
    {
        // Get single image data
        $images = AiImages::where('generate_id', $generateId)->where('generate_by', Auth::user()->id)->first();

        // Check content
        if (isset($images)) {
            return view('user.pages.ai-images.view', compact('images'));
        } else {
            return redirect()->route('user.all.ai.images')->with('failed', trans('Wrong image ID.'));
        }
    }

    // Delete generate image
    public function deleteAiImage(Request $request)
    {
        // Get single image data
        $images = AiImages::where('generate_id', $request->query('id'))->where('generate_by', Auth::user()->id)->first();

        // Check content
        if (isset($images)) {
            // Delete single image data
            AiImages::where('generate_id', $request->query('id'))->where('generate_by', Auth::user()->id)->update([
                'status' => 0
            ]);

            return redirect()->route('user.all.ai.images')->with('success', trans('Deleted!'));
        } else {
            return redirect()->route('user.all.ai.images')->with('failed', trans('Wrong image ID.'));
        }
    }
}
