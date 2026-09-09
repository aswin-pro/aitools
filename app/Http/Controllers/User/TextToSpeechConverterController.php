<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\TextToSpeechConversionRequest;
use App\Models\GeneratedContent;
use App\Services\TextToSpeechConverterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class TextToSpeechConverterController extends Controller
{
    // index
    public function index(Request $request): Response
    {
        // return view
        return Inertia::render('user/text-to-speech-converter/index', [
            'conversions' => fn() => GeneratedContent::dataWithPagination(
                search: $request->search,
                perPage: $request->integer('per_page', 10),
                scope: 'user',
                type: 'text-to-speech'
            ),
        ]);
    }

    // convert
    public function convert(): Response
    {
        // return view
        return Inertia::render('user/text-to-speech-converter/convert/index', [
            'response' => session()->get('response') ?? [
                'name'          => '',
                'conversion'    => '',
                'generation_id' => null,
            ],
        ]);
    }

    // convert to speech
    public function convertToSpeech(TextToSpeechConversionRequest $request): RedirectResponse
    {
        // check plan validity
        if (! hasPlanValidity() || ! hasFutureOnPlan('text-to-speech')) {
            return back()->with('error', 'Please upgrade your plan to use this feature.');
        }
        
        // check limit excedded
        if (hasExceededCredits('text_to_speech')) {
            return back()->with('error', 'credits_exceeded');
        }

        // response
        $response = new TextToSpeechConverterService($request)->generate();

        // if null return error
        if (! $response) {
            return back()->with('error', 'Failed to convert.');
        }

        // return back
        return back()->with('response', $response);
    }

    // delete content
    public function destroy(string $id): RedirectResponse
    {
        // audio
        $audio = GeneratedContent::getDataByField(
            field: 'generation_id',
            value: $id,
            userId: Auth::user()->id
        );

        if ($audio) {
            try {
                // audio path
                $path = ltrim($audio->content, '/');

                if (str_starts_with($path, 'storage/')) {
                    // storage/app/public/...
                    Storage::disk('public')->delete(str_replace('storage/', '', $path));
                } elseif (str_starts_with($path, 'audio/')) {
                    // public/audio/...
                    File::delete(public_path($path));
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to delete audio.');
            }

            // delete
            $audio->delete();
        }

        // return back
        return back();
    }
}
