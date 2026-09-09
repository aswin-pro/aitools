<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PreferenceRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PreferenceController extends Controller
{
    // index
    public function index()
    {
        // app enabled languages
        $languages = config('app.languages');

        $languageList = array_map(function ($key, $value) {
            return [
                'lang_name' => "$value (" . strtoupper($key) . ")",
                'lang_key'  => $key,
            ];
        }, array_keys($languages), array_values($languages));

        return Inertia::render('user/settings/preferences/index', [
            'languages' => $languageList,
        ]);
    }

    // update
    public function update(PreferenceRequest $request)
    {
        // update
        User::where('id', Auth::user()->id)->update([
            'lang' => $request->language,
        ]);

        // redirect
        return back();
    }
}
