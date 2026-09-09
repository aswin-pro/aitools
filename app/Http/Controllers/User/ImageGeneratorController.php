<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ImageGenerationRequest;
use App\Models\GeneratedImage;
use App\Services\ImageGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ImageGeneratorController extends Controller
{
    // index
    public function index(Request $request): Response
    {
        // return view
        return Inertia::render('user/image-generator/index', [
            'images' => fn() => GeneratedImage::dataWithPagination(
                search: $request->search,
                perPage: $request->integer('per_page', 6),
                scope: 'user'
            ),
        ]);
    }

    // generate
    public function generate(): Response
    {
        // return view
        return Inertia::render('user/image-generator/generate/index', [
            'response' => session()->get('response') ?? [
                'image' => '',
            ],
        ]);
    }

    // generate image
    public function generateImage(ImageGenerationRequest $request): RedirectResponse
    {
        // check plan validity
        if (! hasPlanValidity()) {
            return back()->with('error', 'Please upgrade your plan to use this feature.');
        }

        // check limit excedded
        if (hasExceededCredits('image')) {
            return back()->with('error', 'credits_exceeded');
        }

        // Generate image
        $response = new ImageGeneratorService($request)->generate();

        // if null return error
        if (! $response) {
            return back()->with('error', 'Failed to generate image.');
        }

        // return back
        return back()->with('response', $response);
    }

    // destroy
    public function destroy(string $id): RedirectResponse
    {
        // image
        $image = GeneratedImage::getDataByField(
            field: 'generation_id',
            value: $id,
            userId: Auth::user()->id
        );

        if ($image) {
            try {
                foreach ($image->result ?? [] as $path) {
                    // remove leading slash
                    $path = ltrim($path, '/');

                    if (str_starts_with($path, 'storage/')) {
                        // storage/app/public/...
                        Storage::disk('public')->delete(
                            str_replace('storage/', '', $path)
                        );
                    } elseif (str_starts_with($path, 'images/')) {
                        // public/images/...
                        File::delete(public_path($path));
                    }
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to delete image.');
            }

            // update status
            $image->delete();
        }

        // return back
        return back();
    }
}
