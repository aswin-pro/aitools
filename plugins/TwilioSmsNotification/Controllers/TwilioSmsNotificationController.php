<?php

namespace Plugins\TwilioSmsNotification\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class TwilioSmsNotificationController extends Controller
{
    public function twilioSmsNotificationSettings(Request $request)
    {
        // check database and create table if not exists
        if (! Schema::hasTable('twilio_sms_notification_settings')) {
            // Create table
            Schema::create('twilio_sms_notification_settings', function (Blueprint $table) {
                $table->id();
                $table->string('account_sid')->nullable();
                $table->string('auth_token')->nullable();
                $table->string('from_number')->nullable();
                $table->string('admin_number')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });
        }

        // Check if table twilio_sms_notification_templates exists
        if (! Schema::hasTable('twilio_sms_notification_templates')) {
            // Create table
            Schema::create('twilio_sms_notification_templates', function (Blueprint $table) {
                $table->id();
                $table->string('template_name')->nullable();
                $table->string('template_sid')->nullable();
                $table->boolean('is_enabled')->default(0);
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });

            // insert default template
            DB::table('twilio_sms_notification_templates')->insert([
                [
                    'id'            => 1,
                    'template_name' => 'New User Registration Admin',
                    'template_sid'  => '',
                    'is_enabled'    => 0,
                ],
                [
                    'id'            => 2,
                    'template_name' => 'Plan Purchase Admin',
                    'template_sid'  => '',
                    'is_enabled'    => 0,
                ],
                [
                    'id'            => 3,
                    'template_name' => 'Plan Purchase User',
                    'template_sid'  => '',
                    'is_enabled'    => 0,
                ],
                [
                    'id'            => 4,
                    'template_name' => 'Plan Renewal Admin',
                    'template_sid'  => '',
                    'is_enabled'    => 0,
                ],
                [
                    'id'            => 5,
                    'template_name' => 'Plan Renewal User',
                    'template_sid'  => '',
                    'is_enabled'    => 0,
                ],
                [
                    'id'            => 6,
                    'template_name' => 'User Plan Expiry Remainder',
                    'template_sid'  => '',
                    'is_enabled'    => 0,
                ],
                [
                    'id'            => 7,
                    'template_name' => 'User Plan Expired Notification',
                    'template_sid'  => '',
                    'is_enabled'    => 0,
                ],
            ]);
        }

        // notification settings
        $twilio_sms_notification_settings = DB::table('twilio_sms_notification_settings')->first();

        // notification templates
        $twilio_sms_notification_templates =
            DB::table('twilio_sms_notification_templates')
            ->get()
            ->keyBy('template_name');


        return Inertia::render('admin/plugins/twilio-sms-notification/index', [
            'twilio_sms_notification_settings' => $twilio_sms_notification_settings,
            'twilio_sms_notification_templates' => $twilio_sms_notification_templates,
        ]);
    }

    // Update Twilio Sms Notification Settings
    public function twilioSmsNotificationSettingsUpdate(Request $request)
    {
        // Validate request
        $request->validate([
            'account_sid'  => 'required',
            'auth_token'   => 'required',
            'from_number'  => 'required',
            'admin_number' => 'required',
        ]);

        // Update or insert
        DB::table('twilio_sms_notification_settings')->updateOrInsert(
            ['id' => 1],
            [
                'account_sid'  => $request->account_sid,
                'auth_token'   => $request->auth_token,
                'from_number'  => ltrim(str_replace(' ', '', $request->from_number), '+'),
                'admin_number' => ltrim(str_replace(' ', '', $request->admin_number), '+'),
                'updated_at'   => now(),
            ]
        );

        // forget cache
        cache()->forget('twilio_sms_notification_settings');

        // redirect
        return redirect()->back()->with('success', __('Twilio Sms Notification Settings updated successfully.'));
    }

    // Update Twilio Sms Notification User Register Template
    public function twilioSmsTemplateUserRegisterUpdate(Request $request)
    {
        // validate request
        if ($r = $this->validateTemplate($request, [
            'new_user_registration_admin' => 'required',
            'new_user_registration_admin_template_sid' => 'required_if:new_user_registration_admin,1',
        ])) return $r;

        // update templates
        $this->updateTemplates([
            'New User Registration Admin' => [
                'sid'     => $request->new_user_registration_admin_template_sid,
                'enabled' => $request->new_user_registration_admin,
            ],
        ]);

        // redirect
        return back()->with('success', __('Template updated successfully.'));
    }



    // Update Twilio Sms Notification Plan Purchase Template
    public function twilioSmsTemplatePlanPurchaseUpdate(Request $request)
    {
        // validate request
        if ($r = $this->validateTemplate($request, [
            'plan_purchase_admin' => 'required',
            'plan_purchase_admin_template_sid' => 'required_if:plan_purchase_admin,1',
            'plan_purchase_user'  => 'required',
            'plan_purchase_user_template_sid'  => 'required_if:plan_purchase_user,1',
        ])) return $r;

        // update templates
        $this->updateTemplates([
            'Plan Purchase Admin' => [
                'sid' => $request->plan_purchase_admin_template_sid,
                'enabled' => $request->plan_purchase_admin,
            ],
            'Plan Purchase User' => [
                'sid' => $request->plan_purchase_user_template_sid,
                'enabled' => $request->plan_purchase_user,
            ],
        ]);

        // redirect
        return back()->with('success', __('Template updated successfully.'));
    }




    // Update Twilio Sms Notification Plan Renewal Template
    public function twilioSmsTemplatePlanRenewalUpdate(Request $request)
    {
        // validate request
        if ($r = $this->validateTemplate($request, [
            'plan_renewal_admin' => 'required',
            'plan_renewal_admin_template_sid' => 'required_if:plan_renewal_admin,1',
            'plan_renewal_user'  => 'required',
            'plan_renewal_user_template_sid'  => 'required_if:plan_renewal_user,1',
        ])) return $r;

        // update templates
        $this->updateTemplates([
            'Plan Renewal Admin' => [
                'sid' => $request->plan_renewal_admin_template_sid,
                'enabled' => $request->plan_renewal_admin,
            ],
            'Plan Renewal User' => [
                'sid' => $request->plan_renewal_user_template_sid,
                'enabled' => $request->plan_renewal_user,
            ],
        ]);

        // redirect
        return back()->with('success', __('Template updated successfully.'));
    }



    // Update Twilio Sms Notification Plan Expiry Template
    public function twilioSmsTemplateUserPlanExpiryRemainderUpdate(Request $request)
    {
        // Validate request
        if ($r = $this->validateTemplate($request, [
            'user_plan_expiry_remainder'              => 'required',
            'user_plan_expiry_remainder_template_sid' => 'required_if:user_plan_expiry_remainder,1',
        ])) return $r;

        // update templates
        $this->updateTemplates([
            'User Plan Expiry Remainder' => [
                'sid' => $request->user_plan_expiry_remainder_template_sid,
                'enabled' => $request->user_plan_expiry_remainder,
            ]
        ]);

        // redirect
        return back()->with('success', __('Template updated successfully.'));
    }





    // Update Twilio Sms Notification Plan Expired Template
    public function twilioSmsTemplateUserExpiredUpdate(Request $request)
    {
        // Validate request
        if ($r = $this->validateTemplate($request, [
            'user_plan_expired_notification'              => 'required',
            'user_plan_expired_notification_template_sid' => 'required_if:user_plan_expired_notification,1',
        ])) return $r;

        // update templates
        $this->updateTemplates([
            'User Plan Expired Notification' => [
                'sid' => $request->user_plan_expired_notification_template_sid,
                'enabled' => $request->user_plan_expired_notification,
            ]
        ]);

        // redirect
        return back()->with('success', __('Template updated successfully.'));
    }

    // Validate Template Details
    private function validateTemplate(Request $request, array $rules)
    {
        // validator
        $validator = Validator::make($request->all(), $rules);

        // check validation fails
        if ($validator->fails()) {
            return redirect()
                ->route('admin.plugin.twilio_sms_notification.settings')
                ->with('failed', __('Template SID is required when the notification is enabled.'));
        }

        // return null if validation is successful
        return null;
    }

    // Update Templates
    private function updateTemplates(array $templates)
    {
        // update templates
        foreach ($templates as $templateName => $data) {
            DB::table('twilio_sms_notification_templates')
                ->where('template_name', $templateName)
                ->update([
                    'template_sid' => $data['sid'],
                    'is_enabled'   => $data['enabled'],
                    'updated_at'   => now(),
                ]);
        }
    }
}
