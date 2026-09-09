<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    // index
    public function index(): Response
    {
        return Inertia::render('user/settings/profile/index');
    }

    // update
    public function update(ProfileRequest $request): RedirectResponse
    {
        // data
        $updateData = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        // Upload image
        if ($request->hasFile('profile_image')) {
            // file
            $file = $request->file('profile_image');

            // file name
            $fileName = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_FILENAME
            );

            // extension
            $extension = $file->getClientOriginalExtension();

            // file name
            $fileName = $fileName . '_' . uniqid() . '.' . $extension;

            // move to public
            $file->move(
                public_path('images/user/profile_images'),
                $fileName
            );

            // profile image
            $updateData['profile_image'] = 'images/user/profile_images/' . $fileName;
        }

        // update
        User::where('id', Auth::user()->id)->update($updateData);

        // redirect
        return back();
    }
}