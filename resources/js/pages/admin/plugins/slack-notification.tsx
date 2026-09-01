import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Switch } from "@/components/ui/switch";
import { BreadcrumbItem, SharedData } from "@/types";
import { Head, Link, useForm, usePage } from "@inertiajs/react";
import { ArrowLeft, ExternalLink } from "lucide-react";
import { useTranslation } from "react-i18next";
import { toast } from "sonner";
import { LoadingSwap } from "@/components/ui/loading-swap";
import FormInput from "@/components/admin/form-input";

interface SlackSettings {
    slack_webhook_url: string | null;
    user_registration: number;
    plan_purchase: number;
    plan_renewal: number;
    error_logging: number;
}

interface Props {
    slack_settings: SlackSettings | null;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Plugins",
        href: route("dashboard.admin.plugins.index"),
    },
    {
        title: "Slack Settings",
        href: "#",
    },
];

const notificationOptions = [
    {
        key: "user_registration",
        label: "New User Registration Notification",
    },
    {
        key: "plan_purchase",
        label: "User Plan Purchase Notification",
    },
    {
        key: "plan_renewal",
        label: "User Plan Renewal Notification",
    },
    {
        key: "error_logging",
        label: "Error Logs",
    },
] as const;

export default function SlackNotification({ slack_settings }: Props) {
    const { t } = useTranslation();
    const { flash } = usePage<SharedData>().props;

    const form = useForm({
        slack_webhook_url: slack_settings?.slack_webhook_url ?? "",
        user_registration: slack_settings?.user_registration === 1,
        plan_purchase: slack_settings?.plan_purchase === 1,
        plan_renewal: slack_settings?.plan_renewal === 1,
        error_logging: slack_settings?.error_logging === 1,
    });

    const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        form.post(route("admin.slack_settings.update"), {
            preserveScroll: true,

            onSuccess: () => {
                toast.success(
                    flash?.success ?? t("Slack Settings Updated Successfully!"),
                );
            },

            onError: () => {
                toast.error(flash?.error ?? t("Invalid Slack Webhook URL!"));
            },
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Slack Notification")} />

            <div className="space-y-6">
                <Heading
                    title={t("Slack Settings")}
                    description={t(
                        "Configure Slack notifications for your application.",
                    )}
                />

                {/* Settings Card */}
                <Card>
                    <form onSubmit={handleSubmit}>
                        <CardHeader>
                            <CardTitle className="text-base">
                                {t("Slack Notification Credentials")}
                            </CardTitle>
                        </CardHeader>

                        <CardContent className="space-y-8 border-t ">
                            <div className="space-y-4 mt-5">
                                <FormInput
                                    id="slack_webhook_url"
                                    name="slack_webhook_url"
                                    type="url"
                                    label={t("Webhook URL")}
                                    value={form.data.slack_webhook_url}
                                    placeholder={t("Webhook URL")}
                                    error={form.errors.slack_webhook_url}
                                    onChange={(e) => {
                                        form.setData(
                                            "slack_webhook_url",
                                            e.target.value,
                                        );
                                        form.clearErrors("slack_webhook_url");
                                    }}
                                />

                                <p className="flex flex-wrap items-center gap-1 text-sm text-muted-foreground">
                                    {t(
                                        "If you did not get a Slack Webhook URL, create a",
                                    )}

                                    <a
                                        href="https://api.slack.com/apps"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="inline-flex items-center gap-1 underline underline-offset-4"
                                    >
                                        {t("new Slack Webhook URL")}
                                        <ExternalLink className="size-3" />
                                    </a>
                                </p>
                            </div>

                            {/* Notification Controls */}
                            <div className="space-y-4">
                                <div>
                                    <h3 className="text-sm font-medium">
                                        {t("Notification Controls")}
                                    </h3>

                                    <p className="mt-1 text-sm text-muted-foreground">
                                        {t(
                                            "Choose which events should send notifications to Slack.",
                                        )}
                                    </p>
                                </div>

                                <div className="divide-y rounded-lg border">
                                    {notificationOptions.map((notification) => (
                                        <div
                                            key={notification.key}
                                            className="flex items-center justify-between gap-4 px-4 py-4"
                                        >
                                            <Label
                                                htmlFor={notification.key}
                                                className="cursor-pointer"
                                            >
                                                {t(notification.label)}
                                            </Label>

                                            <Switch
                                                id={notification.key}
                                                checked={
                                                    form.data[notification.key]
                                                }
                                                onCheckedChange={(checked) =>
                                                    form.setData(
                                                        notification.key,
                                                        checked,
                                                    )
                                                }
                                            />
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter className="justify-end border-t py-4">
                            <Button
                                type="submit"
                                disabled={form.processing}
                                // className="mt-3"
                            >
                                <LoadingSwap isLoading={form.processing}>
                                    Update
                                </LoadingSwap>
                            </Button>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </AppLayout>
    );
}
