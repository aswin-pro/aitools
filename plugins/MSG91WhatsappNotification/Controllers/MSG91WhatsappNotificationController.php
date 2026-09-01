<?php

namespace Plugins\MSG91WhatsappNotification\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class MSG91WhatsappNotificationController extends Controller
{
    public function msg91WhatsappNotificationSettings(Request $request)
    {
        // check database and create table if not exists
        if (!Schema::hasTable('msg91_whatsapp_notification_settings')) {
            // Create table
            Schema::create('msg91_whatsapp_notification_settings', function (Blueprint $table) {
                $table->id();
                $table->string('auth_key')->nullable();
                $table->string('sender_id')->nullable();
                $table->string('admin_number')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });
        }

        // Check if table msg91_whatsapp_notification_templates exists
        if (!Schema::hasTable('msg91_whatsapp_notification_templates')) {
            // Create table
            Schema::create('msg91_whatsapp_notification_templates', function (Blueprint $table) {
                $table->id();
                $table->string('template_name')->nullable();
                $table->string('template_id')->nullable();
                $table->string('namespace')->nullable();
                $table->json('variables')->nullable();
                $table->boolean('is_enabled')->default(0);
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });

            // insert default template
            DB::table('msg91_whatsapp_notification_templates')->insert([
                [
                    'id'            => 1,
                    'template_name' => 'New User Registration Admin',
                    'template_id'  => '',
                    'namespace'    => '',
                    'variables'    => json_encode([]),
                    'is_enabled'    => 0,
                ],
                [
                    'id'            => 2,
                    'template_name' => 'Plan Purchase Admin',
                    'template_id'  => '',
                    'namespace'    => '',
                    'variables'    => json_encode([]),
                    'is_enabled'    => 0,
                ],
                [
                    'id'            => 3,
                    'template_name' => 'Plan Purchase User',
                    'template_id'  => '',
                    'namespace'    => '',
                    'variables'    => json_encode([]),
                    'is_enabled'    => 0,
                ],
                [
                    'id'            => 4,
                    'template_name' => 'Plan Renewal Admin',
                    'template_id'  => '',
                    'namespace'    => '',
                    'variables'    => json_encode([]),
                    'is_enabled'    => 0,
                ],
                [
                    'id'            => 5,
                    'template_name' => 'Plan Renewal User',
                    'template_id'  => '',
                    'namespace'    => '',
                    'variables'    => json_encode([]),
                    'is_enabled'    => 0,
                ],
                [
                    'id'            => 6,
                    'template_name' => 'User Plan Expiry Remainder',
                    'template_id'  => '',
                    'namespace'    => '',
                    'variables'    => json_encode([]),
                    'is_enabled'    => 0,
                ],
                [
                    'id'            => 7,
                    'template_name' => 'User Plan Expired Notification',
                    'template_id'  => '',
                    'namespace'    => '',
                    'variables'    => json_encode([]),
                    'is_enabled'    => 0,
                ],
            ]);
        }

        // notification settings
        $msg91_whatsapp_notification_settings  = DB::table('msg91_whatsapp_notification_settings')->first();

        // notification templates
        $msg91_whatsapp_notification_templates =
            DB::table('msg91_whatsapp_notification_templates')
            ->get()
            ->keyBy('template_name');


        return Inertia::render('admin/plugins/msg91-whatsapp-notification/index', [

            'msg91_whatsapp_notification_settings' => $msg91_whatsapp_notification_settings,
            'msg91_whatsapp_notification_templates' => $msg91_whatsapp_notification_templates
        ]
        )    ;

    }

    // Update Msg91 Whatsapp Notification Settings
    public function msg91WhatsappNotificationSettingsUpdate(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'auth_key'  => 'required',
            'sender_id'   => 'required',
             'admin_number' => 'required|regex:/^[0-9]{10,15}$/',
        ]);


        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Update or insert
        DB::table('msg91_whatsapp_notification_settings')->updateOrInsert(
            ['id' => 1],
            [
                'auth_key'  => $request->auth_key,
                'sender_id'   => $request->sender_id,
                'admin_number' => ltrim(str_replace(' ', '', $request->admin_number), '+'),
                'updated_at'   => now(),
            ]
        );

        // forget cache
        cache()->forget('msg91_whatsapp_notification_settings');

        // redirect to settings
        return redirect()->back()->with('success', __('MSG91 Whatsapp Notification Settings Updated Successfully!'));
    }

    // User Registration Template Update
    public function msg91WhatsappTemplateUserRegisterUpdate(Request $request)
    {
        // validate request
        if ($r = $this->validateTemplate($request, [
            'new_user_registration_admin' => 'required',
            'new_user_registration_admin_template_id' => 'required_if:new_user_registration_admin,1',
            'new_user_registration_admin_template_namespace' => 'required_if:new_user_registration_admin,1',
        ])) return $r;

        // update templates
        $this->updateTemplates([
            'New User Registration Admin' => [
                'template_id'     => $request->new_user_registration_admin_template_id,
                'namespace'       => $request->new_user_registration_admin_template_namespace,
                'variables'       => $request->variables,
                'enabled'         => $request->new_user_registration_admin,
            ],
        ]);

        // redirect
        return back()->with('success', __('Template updated successfully.'));
    }

    // Plan Purchase Template Update
    public function msg91WhatsappTemplatePlanPurchaseUpdate(Request $request)
    {
        // validate request
        if ($r = $this->validateTemplate($request, [
            'plan_purchase_admin' => 'required',
            'plan_purchase_admin_template_id' => 'required_if:plan_purchase_admin,1',
            'plan_purchase_admin_template_namespace' => 'required_if:plan_purchase_admin,1',
            'plan_purchase_user'  => 'required',
            'plan_purchase_user_template_id'  => 'required_if:plan_purchase_user,1',
            'plan_purchase_user_template_namespace'  => 'required_if:plan_purchase_user,1',
        ])) return $r;

        // update templates
        $this->updateTemplates([
            'Plan Purchase Admin' => [
                'template_id' => $request->plan_purchase_admin_template_id,
                'namespace'   => $request->plan_purchase_admin_template_namespace,
                'variables'   => $request->variablesAdmin,
                'enabled' => $request->plan_purchase_admin,
            ],
            'Plan Purchase User' => [
                'template_id' => $request->plan_purchase_user_template_id,
                'namespace'   => $request->plan_purchase_user_template_namespace,
                'variables'   => $request->variablesUser,
                'enabled' => $request->plan_purchase_user,
            ],
        ]);

        // redirect
        return back()->with('success', __('Template updated successfully.'));
    }

    // Plan Renewal Template Update
    public function msg91WhatsappTemplatePlanRenewalUpdate(Request $request)
    {
        // validate request
        if ($r = $this->validateTemplate($request, [
            'plan_renewal_admin' => 'required',
            'plan_renewal_admin_template_id' => 'required_if:plan_renewal_admin,1',
            'plan_renewal_admin_template_namespace' => 'required_if:plan_renewal_admin,1',
            'plan_renewal_user'  => 'required',
            'plan_renewal_user_template_id'  => 'required_if:plan_renewal_user,1',
            'plan_renewal_user_template_namespace'  => 'required_if:plan_renewal_user,1',
        ])) return $r;

        // update templates
        $this->updateTemplates([
            'Plan Renewal Admin' => [
                'template_id' => $request->plan_renewal_admin_template_id,
                'namespace'   => $request->plan_renewal_admin_template_namespace,
                'variables'   => $request->variablesAdmin,
                'enabled' => $request->plan_renewal_admin,
            ],
            'Plan Renewal User' => [
                'template_id' => $request->plan_renewal_user_template_id,
                'namespace'   => $request->plan_renewal_user_template_namespace,
                'variables'   => $request->variablesUser,
                'enabled' => $request->plan_renewal_user,
            ],
        ]);

        // redirect
        return back()->with('success', __('Template updated successfully.'));
    }

    // User Plan Expiry Remainder Template Update
    public function msg91WhatsappTemplateUserPlanExpiryRemainderUpdate(Request $request)
    {
        // validate request
        if ($r = $this->validateTemplate($request, [
            'user_plan_expiry_remainder' => 'required',
            'user_plan_expiry_remainder_template_id' => 'required_if:user_plan_expiry_remainder,1',
            'user_plan_expiry_remainder_template_namespace' => 'required_if:user_plan_expiry_remainder,1',
        ])) return $r;

        // update templates
        $this->updateTemplates([
            'User Plan Expiry Remainder' => [
                'template_id' => $request->user_plan_expiry_remainder_template_id,
                'namespace'   => $request->user_plan_expiry_remainder_template_namespace,
                'variables'   => $request->variables,
                'enabled' => $request->user_plan_expiry_remainder,
            ],
        ]);

        // redirect
        return back()->with('success', __('Template updated successfully.'));
    }

    // User Plan Expired Template Update
    public function msg91WhatsappTemplateUserExpiredUpdate(Request $request)
    {
        // validate request
        if ($r = $this->validateTemplate($request, [
            'user_plan_expired_notification' => 'required',
            'user_plan_expired_notification_template_id' => 'required_if:user_plan_expired_notification,1',
            'user_plan_expired_notification_template_namespace' => 'required_if:user_plan_expired_notification,1',
        ])) return $r;

        // update templates
        $this->updateTemplates([
            'User Plan Expired Notification' => [
                'template_id' => $request->user_plan_expired_notification_template_id,
                'namespace'   => $request->user_plan_expired_notification_template_namespace,
                'variables'   => $request->variables,
                'enabled' => $request->user_plan_expired_notification,
            ],
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
                ->route('admin.plugin.msg91_whatsapp_notification.settings')
                ->with('failed', __('Template details are required when the notification is enabled.'));
        }

        // return null if validation is successful
        return null;
    }

    // Update Templates
    private function updateTemplates(array $templates)
    {
        // update templates
        foreach ($templates as $templateName => $data) {
            DB::table('msg91_whatsapp_notification_templates')
                ->where('template_name', $templateName)
                ->update([
                    'template_id' => $data['template_id'],
                    'namespace'   => $data['namespace'],
                    'variables'   => $data['variables'],
                    'is_enabled'   => $data['enabled'],
                    'updated_at'   => now(),
                ]);
        }
    }
}
