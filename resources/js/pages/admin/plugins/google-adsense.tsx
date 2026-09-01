import { Head, Form, usePage } from "@inertiajs/react";
import { useTranslation } from "react-i18next";
import { toast } from "sonner";

import { Card, CardContent, CardFooter } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import FormInput from "@/components/admin/form-input";
import { LoadingSwap } from "@/components/ui/loading-swap";
import AppLayout from "@/layouts/app/app-layout";
import { BreadcrumbItem } from "@/types";

type Settings = {
    adsense_code: string;
};

type PageProps = {
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
        title: "Google AdSense",
        href: "#",
    },
];


export default function GoogleAdSense() {
    const { settings } = usePage<PageProps>().props;

    const { t } = useTranslation();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Google AdSense Settings")} />

            <div className="space-y-6">
                
                    <div>
                        <p className="text-sm text-muted-foreground">
                            {t("Overview")}
                        </p>

                        <h1 className="text-2xl font-semibold tracking-tight">
                            {t("Google AdSense Settings")}
                        </h1>
                    </div>

                <Form
                    action={route("admin.google_adsense_settings.update")}
                    method="post"
                    onSuccess={() => {
                        toast.success(
                            t(
                                "Google AdSense Settings Updated Successfully!",
                            ),
                        );
                    }}
                    onError={() => {
                        toast.error(
                            t("Error updating Google AdSense settings"),
                        );
                    }}
                >
                    {({ errors, processing, clearErrors }) => (
                        <Card>
                            <CardContent className="pt-6">
                                <div className="max-w-3xl space-y-6">
                                    <FormInput
                                        id="adsense_code"
                                        name="adsense_code"
                                        type="text"
                                        label={t("Google AdSense Code")}
                                        defaultValue={
                                            settings?.adsense_code || ""
                                        }
                                        placeholder={t(
                                            "Enter Google AdSense code",
                                        )}
                                        required
                                        error={errors.adsense_code}
                                        onChange={() =>
                                            clearErrors("adsense_code")
                                        }
                                    />

                                    <div className="space-y-1 text-sm text-muted-foreground">
                                        <p>
                                            {t(
                                                "Type DISABLE_ADSENSE_ONLY to enable Webtools without AdSense.",
                                            )}
                                        </p>

                                        <p>
                                            {t(
                                                "Enter your AdSense code to enable Webtools with AdSense.",
                                            )}
                                        </p>

                                        <p>
                                            {t(
                                                "Type DISABLE_BOTH to disable Webtools and AdSense.",
                                            )}
                                        </p>

                                        <p className="pt-2">
                                            {t(
                                                "If you did not get a Google AdSense code, create a",
                                            )}{" "}
                                            <a
                                                href="https://www.google.com/adsense/new"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="font-medium text-primary hover:underline"
                                            >
                                                {t("new AdSense code.")}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </CardContent>

                            <CardFooter className="justify-end border-t pt-6">
                                <Button
                                    type="submit"
                                    disabled={processing}
                                >
                                    <LoadingSwap isLoading={processing}>
                                        {t("Update")}
                                    </LoadingSwap>
                                </Button>
                            </CardFooter>
                        </Card>
                    )}
                </Form>
            </div>
        </AppLayout>
    );
}