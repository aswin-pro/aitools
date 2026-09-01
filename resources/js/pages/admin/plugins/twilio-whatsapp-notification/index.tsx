import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";

import TwilioCredentials from "./twiliocredentials";
import { BreadcrumbItem } from "@/types";
import { useTranslation } from "react-i18next";
import { Card, CardContent } from "@/components/ui/card";
import NewUserRegistration from "./new-user-regitration";
import PlanPurchase from "./plan-purchase";
import PlanRenewal from "./plan-renewal";
import PlanExpiryReminder from "./plan-expiry-reminder";
import PlanExpiredNotification from "./plan-expired-notification";

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
    twilio_whatsapp_notification_settings: TwilioSettings | null;
    twilio_whatsapp_notification_templates: Record<string, TwilioTemplate>;
}

export default function TwilioSmsSettings({
    twilio_whatsapp_notification_settings,
    twilio_whatsapp_notification_templates,
}: Props) {
    const { t } = useTranslation();

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: t("Dashboard"),
            href: route("dashboard.admin.overview"),
        },
        {
            title: t("Plugins"),
            href: route("dashboard.admin.plugins.index"),
        },
        {
            title: t("Twilio Whatsapp Notification"),
            href: "#",
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Heading
                title={t("Twilio Whatsapp Notification Settings")}
                description={t(
                    "Configure Twilio Whatsapp notification credentials and templates.",
                )}
            />

            <Card>
                <CardContent>
                    <div className="pt-10">
                        <TwilioCredentials
                            settings={twilio_whatsapp_notification_settings}
                        />
                    </div>
                </CardContent>
            </Card>

            <div className="mt-10">
                <Card>
                    <CardContent>
                        <div className="pt-10">
                            <NewUserRegistration
                                template={
                                    twilio_whatsapp_notification_templates[
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
                        <div className="pt-10">
                            <PlanPurchase
                                adminTemplate={
                                    twilio_whatsapp_notification_templates[
                                        "Plan Purchase Admin"
                                    ]
                                }
                                userTemplate={
                                    twilio_whatsapp_notification_templates[
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
                        <div className="pt-10">
                            <PlanRenewal
                                adminTemplate={
                                    twilio_whatsapp_notification_templates[
                                        "Plan Renewal Admin"
                                    ]
                                }
                                userTemplate={
                                    twilio_whatsapp_notification_templates[
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
                        <div className="pt-10">
                            <PlanExpiryReminder
                                template={
                                    twilio_whatsapp_notification_templates[
                                        "User Plan Expiry Remainder"
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
                        <div className="pt-10">
<PlanExpiredNotification
    template={
        twilio_whatsapp_notification_templates[
            "User Plan Expired Notification"
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
