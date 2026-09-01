import Heading from "@/components/heading";
import { Card, CardContent } from "@/components/ui/card";
import AppLayout from "@/layouts/app/app-layout";
import { BreadcrumbItem } from "@/types";
import { Head } from "@inertiajs/react";
import { useTranslation } from "react-i18next";
import UserRegistration from "./user-registration";
import MSG91WhatsappCredentials from "./msg91-whatsapp-credentials";
import PlanPurchase from "./plan-purchase";
import PlanRenewal from "./plan-renewal";
import PlanExpiryReminder from "./plan-expiry-reminder";
import PlanExpiredNotification from "./plan-expired-notification";

interface MSG91WhatsappSettings {
    auth_key: string | null;
    sender_id: string | null;
    admin_number: string | null;
}

interface MSG91WhatsappTemplate {
    id: number;
    template_name: string;
    template_id: string | null;
    namespace: string | null;
    variables: string | null;
    is_enabled: number;
}

interface Props {
    msg91_whatsapp_notification_settings: MSG91WhatsappSettings | null;

    msg91_whatsapp_notification_templates: Record<
        string,
        MSG91WhatsappTemplate
    >;
}

export default function MSG91WhatsappSettings({
    msg91_whatsapp_notification_settings,
    msg91_whatsapp_notification_templates,
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
            title: t("MSG91 Whatsapp Notification"),
            href: "#",
        },
    ];

    return (
        <>
            <Head title={t("MSG91 Whatsapp Notification")} />

            <AppLayout breadcrumbs={breadcrumbs}>
                <Heading
                    title={t("MSG91 Whatsapp Notification Settings")}
                    description={t(
                        "Configure MSG91 Whatsapp notification credentials and templates.",
                    )}
                />

                <Card>
                    <CardContent>
                        <div className="pt-10">
                            <MSG91WhatsappCredentials
                                settings={msg91_whatsapp_notification_settings}
                            />
                        </div>
                    </CardContent>
                </Card>

                <div className="mt-10">
                    <Card>
                        <CardContent>
                            <div className="pt-10 space-y-10">
                                <UserRegistration
                                    template={
                                        msg91_whatsapp_notification_templates[
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
                            <div className="pt-10 space-y-10">
                                <PlanPurchase
                                    adminTemplate={
                                        msg91_whatsapp_notification_templates[
                                            "Plan Purchase Admin"
                                        ]
                                    }
                                    userTemplate={
                                        msg91_whatsapp_notification_templates[
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
                            <div className="pt-10 space-y-10">
                                <PlanRenewal
                                    adminTemplate={
                                        msg91_whatsapp_notification_templates[
                                            "Plan Renewal Admin"
                                        ]
                                    }
                                    userTemplate={
                                        msg91_whatsapp_notification_templates[
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
                            <div className="pt-10 space-y-10">
                                <PlanExpiryReminder
                                    template={
                                        msg91_whatsapp_notification_templates[
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
                            <div className="pt-10 space-y-10">
                                <PlanExpiredNotification
                                    template={
                                        msg91_whatsapp_notification_templates[
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
