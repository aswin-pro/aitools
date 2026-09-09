<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PasswordController extends Controller
{
    // index
    public function index(): Response
    {
        return Inertia::render('user/settings/password/index');
    }

    // Update password
    public function update(PasswordRequest $request): RedirectResponse
    {
        // update
        User::where('id', Auth::user()->id)->update([
            'password' => bcrypt($request->password),
        ]);

        // return back
        return back();
    }
}