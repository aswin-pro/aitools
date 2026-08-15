<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Config;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Resonse;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rules\Email;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
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

    // My account
    public function index()
    {
        // Queries
        $account_details = User::where('id', auth()->user()->id)->where('status', 1)->first();
        $settings = Setting::where('status', 1)->first();
        $config = Config::get();

        return Inertia::render('admin/settings/profile', compact('account_details', 'settings', 'config'));

        // return view('admin.pages.account.index', compact('account_details', 'settings', 'config'));
    }

    // Edit account
    public function editAccount()
    {
        // Queries
        $account_details = User::where('id', auth()->user()->id)->where('status', 1)->first();
        $settings = Setting::where('status', 1)->first();
        $config = Config::get();

        return Inertia::render('admin/settings/profile', compact('account_details', 'settings', 'config'));
        // return view('admin.pages.profile', compact('account_details', 'settings', 'config'));
    }

    public function updateAccount(Request $request)
    {
        
        $user = User::findOrFail(auth()->id());

        // Validate request
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                'unique:users,email,' . $user->id,
            ],
            'profile_picture' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,svg,webp',
                'max:' . env('SIZE_LIMIT'),
            ],
        ]);

        // Upload profile picture
        if ($request->hasFile('profile_picture')) {

            // Delete old image
            if (
                $user->profile_image &&
                File::exists(public_path($user->profile_image))
            ) {
                File::delete(public_path($user->profile_image));
            }

            $file = $request->file('profile_picture');

            $filename = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_FILENAME
            );

            $extension = $file->getClientOriginalExtension();

            $imageName = $filename . '_' . uniqid() . '.' . $extension;

            $file->move(
                public_path('images/admin/profile_images'),
                $imageName
            );

            $user->profile_image = 'images/admin/profile_images/' . $imageName;
        }

        // Update profile
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        $user->save();

        return redirect()
            ->route('dashboard.admin.edit.account')
            ->with('success', __('Profile updated successfully!'));
    }

    // Change password
    public function changePassword()
    {
        // Queries
        $account_details = User::where('id', auth()->user()->id)->where('status', 1)->first();
        $settings = Setting::where('status', 1)->first();
        $config = Config::get();

        return Inertia::render('admin/settings/password', compact('account_details', 'settings', 'config'));

        // return view('admin.pages.account.change-password', compact('account_details', 'settings', 'config'));
    }

    public function updatePassword(Request $request)
    {
    $validated = $request->validate([
        'current_password' => [
            'required',
            'current_password',
        ],

        'new_password' => [
            'required',
            'string',
            'min:8',
            'max:255',
        ],

        'confirm_password' => [
            'required',
            'same:new_password',
        ],
    ],
    [
        'current_password.required' => 'Please enter your current password',
        'current_password.current_password' => 'The current password is incorrect',

        'new_password.required' => 'Please enter a new password.',
        'new_password.min' => 'The new password must be at least 8 characters.',

        'new_password.confirmed' => 'The passwords do not match.',
        'confrim_password.required' => 'Please enter a confirm password'
    ]
    
    );

    auth()->user()->update([
        'password' => Hash::make($validated['new_password']),
    ]);

    return redirect()
        ->route('dashboard.admin.change.password')
        ->with('success', __('Password updated successfully!'));
    }

    // Change theme
    public function changeTheme($id)
    {
        // Update Password
        User::where('id', auth()->user()->id)->update([
            'choosed_theme' => $id
        ]);

        return redirect()->route('dashboard.admin.overview');
    }
}
