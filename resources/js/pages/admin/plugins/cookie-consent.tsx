import { Head, router, useForm } from "@inertiajs/react";
import { useTranslation } from "react-i18next";

import { Button } from "@/components/ui/button";
import { Card, CardContent, CardFooter } from "@/components/ui/card";
import { Label } from "@/components/ui/label";
import AppLayout from "@/layouts/app/app-layout";
import { BreadcrumbItem } from "@/types";
import Heading from "@/components/heading";
import { Switch } from "@/components/ui/switch";
import { LoadingSwap } from "@/components/ui/loading-swap";

interface CookieConsentProps {
    cookieConsentEnabled: boolean;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Plugins",
        href: "#",
    },
    {
        title: "Cookie Consent",
        href: "#",
    },
];

export default function CookieConsent({
    cookieConsentEnabled,
}: CookieConsentProps) {
    const { t } = useTranslation();

    const form = useForm({
        cookie_consent: cookieConsentEnabled ? "1" : "0",
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        form.post(route("admin.plugin.cookie-consent.update"), {
            preserveScroll: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Cookie Consent")} />
            <div className="space-y-6">
                <div className="mb-6">
                    <Heading
                        title={t("Overview")}
                        description={t("Cookie Consent Settings")}
                    />
                </div>

                <form onSubmit={handleSubmit}>
                    <Card>
                        <CardContent className="pt-6">
                            <div className="flex items-center justify-between gap-6">
                                <div className="space-y-1">
                                    <Label className="text-sm font-medium">
                                        {t("Enable Cookie Consent")}
                                    </Label>

                                    <p className="text-sm text-muted-foreground">
                                        {t(
                                            "Show the cookie consent notice to visitors on your website.",
                                        )}
                                    </p>
                                </div>

                                <Switch
                                    id="cookie-consent"
                                    checked={form.data.cookie_consent === "1"}
                                    onCheckedChange={(checked) => {
                                        form.setData(
                                            "cookie_consent",
                                            checked ? "1" : "0",
                                        );
                                    }}
                                />
                            </div>
                        </CardContent>

                        <CardFooter className="justify-end border-t pt-5">
                            <Button type="submit" disabled={form.processing}>
                                <LoadingSwap isLoading={form.processing}>
                                    {t("Update")}
                                </LoadingSwap>
                            </Button>
                        </CardFooter>
                    </Card>
                </form>
            </div>
        </AppLayout>
    );
}
