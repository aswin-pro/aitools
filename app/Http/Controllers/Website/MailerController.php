<?php

namespace App\Http\Controllers\Website;

use Exception;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailerController extends Controller
{
    // Compose Email
    public function composeEmail(Request $request)
    {
        if (env('RECAPTCHA_ENABLE') == 'on') {
            $validated = Validator::make($request->all(), [
                'emailName' => 'required',
                'emailRecipient' => 'required',
                'emailBody' => 'required',
                'g-recaptcha-response' => ['recaptcha', 'required']
            ]);
        } else {
            $validated = Validator::make($request->all(), [
                'emailName' => 'required',
                'emailRecipient' => 'required',
                'emailBody' => 'required'
            ]);
        }

        if ($validated->fails()) {
            return back()->with('failed', $validated->messages()->all()[0])->withInput();
        }

        try {
            // Contact Details Array
            $contactDetails = [
                'name' => $request->emailName,
                'email' => $request->emailRecipient,
                'message' => $request->emailBody,
            ];

            // Send mail
            Mail::to(env('MAIL_FROM_ADDRESS'))->send(new ContactMail($contactDetails));

            return redirect()->route('web.contact')->with('success', trans('Email sent!'));
        } catch (Exception $e) {
            return back()->with("error", trans("Email service not available."));
        }

        return redirect()->route('web.contact')->with('success', trans('Email sent!'));
    }
}
