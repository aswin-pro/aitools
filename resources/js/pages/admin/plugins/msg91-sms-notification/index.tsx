import Heading from "@/components/heading";
import { Card, CardContent } from "@/components/ui/card";
import AppLayout from "@/layouts/app/app-layout";
import { BreadcrumbItem } from "@/types";
import { Head } from "@inertiajs/react";
import { useTranslation } from "react-i18next";
import MSG91SmsCredentials from "./msg91-sms-credentials";
import NewUserRegistration from "./new-user-registration";
import PlanPurchase from "./plan-purchase";
import PlanRenewal from "./plan-renewal";
import PlanExpiryReminder from "./plan-expiry-reminder";
import PlanExpiredNotification from "./plan-expired-notification";

interface MSG91Settings {
    auth_key: string | null;
    sender_id: string | null;
    admin_number: string | null;
}

interface MSG91Template {
    id: number;
    template_name: string;
    template_id: string | null;
    is_enabled: number;
}

interface Props {
    msg91_sms_notification_settings: MSG91Settings | null;
    msg91_sms_notification_templates: Record<string, MSG91Template>;
}

export default function MSG91SmsSettings({
    msg91_sms_notification_settings,
    msg91_sms_notification_templates,
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
            title: t("MSG91 SMS Notification"),
            href: "#",
        },
    ];
    return (
        <>
            <AppLayout breadcrumbs={breadcrumbs}>
                <Heading
                    title={t("MSG91 SMS Notification Settings")}
                    description={t(
                        "Configure Twilio SMS notification credentials and templates.",
                    )}
                />

                <Card>
                    <CardContent>
                        <div className="pt-10">
                            <MSG91SmsCredentials
                                settings={msg91_sms_notification_settings}
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
                                        msg91_sms_notification_templates[
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
                                        msg91_sms_notification_templates[
                                            "Plan Purchase Admin"
                                        ]
                                    }
                                    userTemplate={
                                        msg91_sms_notification_templates[
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
                                        msg91_sms_notification_templates[
                                            "Plan Renewal Admin"
                                        ]
                                    }
                                    userTemplate={
                                        msg91_sms_notification_templates[
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
                                        msg91_sms_notification_templates[
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
                                        msg91_sms_notification_templates[
                                            "User Plan Expired Notification"
                                        ]
                                    }
                                />
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </AppLayout>
        </>
    );
}
