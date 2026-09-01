import { Form, Head, usePage } from "@inertiajs/react";
import { toast } from "sonner";
import { useTranslation } from "react-i18next";

import FormInput from "@/components/admin/form-input";
import { Button } from "@/components/ui/button";
import { LoadingSwap } from "@/components/ui/loading-swap";
import AppLayout from "@/layouts/app/app-layout";
import { BreadcrumbItem } from "@/types";

type Settings = {
    analytics_id: string;
    google_tag: string;
};

type GoogleAnalyticsPageProps = {
    settings: Settings;
};

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
        title: "Google Analytics",
        href: "#",
    },
];

export default function Index() {
    const { settings } =
        usePage<GoogleAnalyticsPageProps>().props;

    const { t } = useTranslation();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Google Analytics Settings")} />

            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <p className="text-sm text-muted-foreground">
                            {t("Overview")}
                        </p>

                        <h1 className="text-2xl font-semibold tracking-tight">
                            {t("Google Analytics Settings")}
                        </h1>
                    </div>
                </div>

                <div className="rounded-xl border bg-card">
                    <div className="border-b px-6 py-5">
                        <h2 className="font-semibold">
                            {t("Google Analytics Credentials")}
                        </h2>

                        <p className="mt-1 text-sm text-muted-foreground">
                            {t(
                                "Configure Google Analytics and Google Tag settings for your application.",
                            )}
                        </p>
                    </div>

                    <Form
                        action={route(
                            "admin.google_analytics_settings.update",
                        )}
                        method="post"
                        className="p-6"
                        onSuccess={() => {
                            toast.success(
                                t(
                                    "Google Analytics settings updated successfully!",
                                ),
                            );
                        }}
                        onError={() => {
                            toast.error(
                                t(
                                    "Error updating Google Analytics settings",
                                ),
                            );
                        }}
                    >
                        {({ errors, processing, clearErrors }) => (
                            <>
                                <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <FormInput
                                        id="analytics_id"
                                        name="analytics_id"
                                        type="text"
                                        label={t("Google Analytics ID")}
                                        defaultValue={
                                            settings.analytics_id
                                        }
                                        placeholder={t(
                                            "Google Analytics ID",
                                        )}
                                        required
                                        error={errors.analytics_id}
                                        onChange={() =>
                                            clearErrors("analytics_id")
                                        }
                                    />

                                    <FormInput
                                        id="google_tag"
                                        name="google_tag"
                                        type="text"
                                        label={t("Google Tag ID")}
                                        defaultValue={
                                            settings.google_tag
                                        }
                                        placeholder={t(
                                            "Google Tag ID",
                                        )}
                                        error={errors.google_tag}
                                        onChange={() =>
                                            clearErrors("google_tag")
                                        }
                                    />
                                </div>

                                {/* Analytics help */}
                                <p className="mt-4 text-sm text-muted-foreground">
                                    {t(
                                        "If you did not get a Google Analytics ID, create a",
                                    )}{" "}
                                    <a
                                        href="https://analytics.google.com/analytics/web/"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="text-primary hover:underline"
                                    >
                                        {t("new Analytics ID")}
                                    </a>
                                    .
                                </p>

                                {/* Tag help */}
                                <p className="mt-2 text-sm text-muted-foreground">
                                    {t(
                                        "If you did not get a Google Tag ID, create a",
                                    )}{" "}
                                    <a
                                        href="https://support.google.com/analytics/answer/9539598?hl=en"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="text-primary hover:underline"
                                    >
                                        {t("new Tag ID")}
                                    </a>
                                    .
                                </p>

                                <div className="mt-6 flex justify-end border-t pt-6">
                                    <Button
                                        type="submit"
                                        disabled={processing}
                                    >
                                        <LoadingSwap
                                            isLoading={processing}
                                        >
                                            {t("Update")}
                                        </LoadingSwap>
                                    </Button>
                                </div>
                            </>
                        )}
                    </Form>
                </div>
            </div>
        </AppLayout>
    );
}