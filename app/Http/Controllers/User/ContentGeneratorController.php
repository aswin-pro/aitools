<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ContentGenerationRequest;
use App\Http\Requests\User\ContentUpdateRequest;
use App\Models\ContentTemplate;
use App\Models\GeneratedContent;
use App\Services\ContentGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ContentGeneratorController extends Controller
{
    // index
    public function index(Request $request): Response
    {
        // return view
        return Inertia::render('user/content-generator/index', [
            'contents' => fn() => GeneratedContent::dataWithPagination(
                search: $request->search,
                perPage: $request->integer('per_page', 10),
                with: ['template'],
                scope: 'user',
                type: 'content-generator'
            ),
        ]);
    }

    // templates
    public function templates(): Response
    {
        // plan templates
        $plan_templates = userPlanContentTemplates();

        // templates
        $templates = ContentTemplate::getData(with: ['category'])
            ->groupBy('category_id')
            ->map(function ($categoryTemplates) use ($plan_templates) {
                // return category
                return $categoryTemplates->map(function ($template) use ($plan_templates) {
                    $template->is_available = ($plan_templates->{$template->unique_slug} ?? 0) == 1;
                    // return template
                    return $template;
                });
            });

        // return view
        return Inertia::render('user/content-generator/templates/index', [
            'templates' => $templates,
        ]);
    }

    // generate
    public function generate(string $template): Response
    {
        // user plan templates
        $plan_templates = userPlanContentTemplates();

        // Check if template is available in user's plan
        if (($plan_templates->{$template} ?? 0) === 1) {
            // template details
            $template_details = ContentTemplate::getDataByField(
                field: 'unique_slug',
                value: $template,
                with: ['fields']
            );

            // languages
            $languages = getAppLanguages();

            // max words length
            $max_words_length = getMaxWordsLength();

            // return view
            return Inertia::render('user/content-generator/generate/index', [
                'template'       => $template,
                'templateFields' => $template_details->fields,
                'languages'      => $languages,
                'maxWordsLength' => $max_words_length,
                'response'       => session()->get('response') ?? [
                    'generation_id' => null,
                    'content'       => '',
                ],
            ]);
        }

        // return back
        abort(404);
    }

    // Generate content
    public function generateContent(ContentGenerationRequest $request, string $template): RedirectResponse
    {
        // check plan validity
        if (! hasPlanValidity()) {
            return back()->with('error', 'Please upgrade your plan to use this feature.');
        }

        // check limit excedded
        if (hasExceededCredits('content')) {
            return back()->with('error', 'credits_exceeded');
        }

        // response
        $response = new ContentGeneratorService($request, $template)->generate();

        // if null return error
        if (! $response) {
            return back()->with('error', 'Failed to generate content.');
        }

        // return back
        return back()->with('response', $response);
    }

    // Update content
    public function update(ContentUpdateRequest $request, string $id): RedirectResponse
    {
        // content
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
        return to_route('dashboard.user.content-generator.index');
    }

    // destroy
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
