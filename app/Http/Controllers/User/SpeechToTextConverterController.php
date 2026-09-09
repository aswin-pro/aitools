<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ContentUpdateRequest;
use App\Http\Requests\User\SpeechToTextConversionRequest;
use App\Models\GeneratedContent;
use App\Services\SpeechToTextConverterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class SpeechToTextConverterController extends Controller
{
    // index
    public function index(Request $request): Response
    {
        // return view
        return Inertia::render('user/speech-to-text-converter/index', [
            'conversions' => fn() => GeneratedContent::dataWithPagination(
                search: $request->search,
                perPage: $request->integer('per_page', 10),
                scope: 'user',
                type: 'speech-to-text'
            ),
        ]);
    }

    // convert
    public function convert(): Response
    {
        // return view
        return Inertia::render('user/speech-to-text-converter/convert/index', [
            'response' => session()->get('response') ?? [
                'conversion'    => '',
                'generation_id' => null,
            ],
        ]);
    }

    // convert to text
    public function convertToText(SpeechToTextConversionRequest $request)
    {
        // check plan validity
        if (! hasPlanValidity() || ! hasFutureOnPlan('speech-to-text')) {
            return back()->with('error', 'Please upgrade your plan to use this feature.');
        }
        
        // check limit excedded
        if (hasExceededCredits('speech_to_text')) {
            return back()->with('error', 'credits_exceeded');
        }

        // response
        $response = new SpeechToTextConverterService($request)->generate();

        // if null return error
        if (! $response) {
            return back()->with('error', 'Failed to convert.');
        }

        // return back
        return back()->with('response', $response);
    }

    // Save content
    public function update(ContentUpdateRequest $request, string $id): RedirectResponse
    {
        // Update single content data
        $content = GeneratedContent::getDataByField(
            field: 'generation_id',
            value: $id,
            userId: Auth::user()->id
        );

        // Update content
        if ($content) {
            $content->update([
                'content' => $request->content,
            ]);
        }

        // return content generator page
        return to_route('dashboard.user.speech-to-text.index');
    }

    // delete content
    public function destroy(string $id): RedirectResponse
    {
        // delete content
        GeneratedContent::getDataByField(
            field: 'generation_id',
            value: $id,
            userId: Auth::user()->id
        )->delete();

        // return back
        return back();
    }
}
