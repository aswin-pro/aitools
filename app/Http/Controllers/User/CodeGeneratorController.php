<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CodeGenerationRequest;
use App\Models\GeneratedContent;
use App\Services\CodeGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CodeGeneratorController extends Controller
{
    // index
    public function index(Request $request): Response
    {
        // return view
        return Inertia::render('user/code-generator/index', [
            'codes' => fn() => GeneratedContent::dataWithPagination(
                search: $request->search,
                perPage: $request->integer('per_page', 10),
                scope: 'user',
                type: 'code-generator'
            ),
        ]);
    }

    // generate
    public function generate(): Response
    {
        // return view
        return Inertia::render('user/code-generator/generate/index', [
            'response' => session()->get('response') ?? [
                'code' => '',
            ],
        ]);
    }

    // Generate code
    public function generateCode(CodeGenerationRequest $request)
    {
        // check plan validity
        if (! hasPlanValidity() || ! hasFutureOnPlan('code-generator')) {
            return back()->with('error', 'Please upgrade your plan to use this feature.');
        }

        // check limit excedded
        if (hasExceededCredits('code')) {
            return back()->with('error', 'credits_exceeded');
        }

        // response
        $response = new CodeGeneratorService($request)->generate();

        // if null return error
        if (! $response) {
            return back()->with('error', 'Failed to generate content.');
        }

        // return back
        return back()->with('response', $response);
    }

    // destroy
    public function destroy(string $id): RedirectResponse
    {
        // delete code
        GeneratedContent::getDataByField(
            field: 'generation_id',
            value: $id,
            userId: Auth::user()->id
        )->delete();

        // return back
        return back();
    }
}
