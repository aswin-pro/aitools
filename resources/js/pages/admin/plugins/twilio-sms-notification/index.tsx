import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import TwilioCredentials from "./TwilioCredentials";
import { Card, CardContent } from "@/components/ui/card";
import NewUserRegistration from "./new-user-regitration";
import PlanPurchase from "./plan-purchase";
import PlanRenewal from "./plan-renewal";
import PlanExpiryReminder from "./plan-expiry-reminder";

interface TwilioSettings {
    account_sid: string | null;
    auth_token: string | null;
    from_number: string | null;
    admin_number: string | null;
}

interface TwilioTemplate {
    id: number;
    template_name: string;
    template_sid: string | null;
    is_enabled: number;
}

interface Props {
    twilio_sms_notification_settings: TwilioSettings | null;
    twilio_sms_notification_templates: Record<string, TwilioTemplate>;
}

export default function TwilioSmsSettings({
    twilio_sms_notification_settings,
    twilio_sms_notification_templates,
}: Props) {
    console.log(twilio_sms_notification_settings);
    console.log(twilio_sms_notification_templates);

    return (
        <AppLayout>
            <Heading
                title="Twilio SMS Notification Settings"
                description="Configure Twilio SMS notification credentials and templates."
            />

            <Card>
                <CardContent>
                    <div className="py-10">
                        <TwilioCredentials
                            settings={twilio_sms_notification_settings}
                        />{" "}
                    </div>
                </CardContent>
            </Card>

            <div className="mt-10">
                <Card>
                    <CardContent>
                        <div className="py-10">
                            <NewUserRegistration
                                template={
                                    twilio_sms_notification_templates[
                                        "New User Registration Admin"
                                    ]
                                }
                            />
                        </div>
                    </CardContent>
                </Card>
            </div>

            <div className="mt-10">
                <Card>
                    <CardContent>
                        <div className="py-10">
                            <PlanPurchase
                                adminTemplate={
                                    twilio_sms_notification_templates[
                                        "Plan Purchase Admin"
                                    ]
                                }
                                userTemplate={
                                    twilio_sms_notification_templates[
                                        "Plan Purchase User"
                                    ]
                                }
                            />
                        </div>
                    </CardContent>
                </Card>
            </div>

            <div className="mt-10">
                <Card>
                    <CardContent>
                        <div className="py-10">
                            <PlanRenewal
                                adminTemplate={
                                    twilio_sms_notification_templates[
                                        "Plan Renewal Admin"
                                    ]
                                }
                                userTemplate={
                                    twilio_sms_notification_templates[
                                        "Plan Renewal User"
                                    ]
                                }
                            />
                        </div>
                    </CardContent>
                </Card>
            </div>

            <div className="mt-10">
                <Card>
                    <CardContent>
                        <div className="py-10">
                            <PlanExpiryReminder
                                template={
                                    twilio_sms_notification_templates[
                                        "User Plan Expiry Remainder"
                                    ]
                                }
                            />
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
