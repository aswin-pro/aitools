import { Form, Head, usePage } from "@inertiajs/react";
import { toast } from "sonner";
import { useTranslation } from "react-i18next";

import FormInput from "@/components/admin/form-input";
import { SearchableSelect } from "@/components/admin/searchable-select";
import { Button } from "@/components/ui/button";
import { LoadingSwap } from "@/components/ui/loading-swap";
import AppLayout from "@/layouts/app/app-layout";
import { BreadcrumbItem } from "@/types";
import { useState } from "react";

type GoogleConfiguration = {
    GOOGLE_ENABLE: string;
    GOOGLE_CLIENT_ID: string;
    GOOGLE_CLIENT_SECRET: string;
    GOOGLE_REDIRECT: string;
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
        title: "Google OAuth",
        href: "#",
    },
];

type GoogleOAuthPageProps = {
    googleConfiguration: GoogleConfiguration;
};

export default function GoogleOAuth() {
    const { googleConfiguration } = usePage<GoogleOAuthPageProps>().props;

    const [googleEnable, setGoogleEnable] = useState(
        googleConfiguration.GOOGLE_ENABLE || "off",
    );

    const { t } = useTranslation();

    return (
        <>
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title={t("Google OAuth Settings")} />

                <div className="space-y-6">
                    <div className="flex items-center justify-between">
                        <div>
                            <p className="text-sm text-muted-foreground">
                                {t("Overview")}
                            </p>

                            <h1 className="text-2xl font-semibold tracking-tight">
                                {t("Google OAuth Settings")}
                            </h1>
                        </div>
                    </div>

                    <div className="rounded-xl border bg-card">
                        <div className="border-b px-6 py-5">
                            <h2 className="font-semibold">
                                {t("Google OAuth Credentials")}
                            </h2>

                            <p className="mt-1 text-sm text-muted-foreground">
                                {t(
                                    "Configure Google authentication credentials for your application.",
                                )}
                            </p>
                        </div>

                        <Form
                            action={route("admin.google_oauth_settings.update")}
                            method="post"
                            className="p-6"
                            onSuccess={() => {
                                toast.success(
                                    t(
                                        "Google OAuth settings updated successfully!",
                                    ),
                                );
                            }}
                            onError={() => {
                                toast.error(
                                    t("Error updating Google OAuth settings"),
                                );
                            }}
                        >
                            {({ errors, processing, clearErrors }) => (
                                <>
                                    <div className="grid gap-6 md:grid-cols-2">
                                        <SearchableSelect
                                            label={t("Google Auth Enable")}
                                            value={googleEnable}
                                            onChange={(value) => {
                                                setGoogleEnable(value);
                                                clearErrors(
                                                    "google_auth_enable",
                                                );
                                            }}
                                            options={[
                                                {
                                                    value: "on",
                                                    label: t("On"),
                                                },
                                                {
                                                    value: "off",
                                                    label: t("Off"),
                                                },
                                            ]}
                                            placeholder={t(
                                                "Select Google Auth status",
                                            )}
                                            searchable={false}
                                            name="google_auth_enable"
                                            error={errors.google_auth_enable}
                                        />

                                        <FormInput
                                            id="google_client_id"
                                            name="google_client_id"
                                            type="text"
                                            label={t("Google Client ID")}
                                            defaultValue={
                                                googleConfiguration.GOOGLE_CLIENT_ID
                                            }
                                            placeholder={t("Google Client ID")}
                                            error={errors.google_client_id}
                                            onChange={() =>
                                                clearErrors("google_client_id")
                                            }
                                        />

                                        <FormInput
                                            id="google_client_secret"
                                            name="google_client_secret"
                                            type="text"
                                            label={t("Google Client Secret")}
                                            defaultValue={
                                                googleConfiguration.GOOGLE_CLIENT_SECRET
                                            }
                                            placeholder={t(
                                                "Google Client Secret",
                                            )}
                                            error={errors.google_client_secret}
                                            onChange={() =>
                                                clearErrors(
                                                    "google_client_secret",
                                                )
                                            }
                                        />

                                        <FormInput
                                            id="google_redirect"
                                            name="google_redirect"
                                            type="text"
                                            label={t("Google Redirect")}
                                            defaultValue={
                                                googleConfiguration.GOOGLE_REDIRECT
                                            }
                                            readOnly
                                        />
                                    </div>

                                    <div className="mt-6">
                                        <p className="text-sm text-muted-foreground">
                                            {t(
                                                "If you did not get a Google OAuth Client ID & Secret Key, follow the",
                                            )}{" "}
                                            <a
                                                href="https://support.google.com/cloud/answer/6158849"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="text-primary hover:underline"
                                            >
                                                {t("steps")}
                                            </a>
                                            .
                                        </p>
                                    </div>

                                    <div className="mt-6 flex justify-end border-t pt-6">
                                        <Button
                                            type="submit"
                                            disabled={processing}
                                        >
                                            <LoadingSwap isLoading={processing}>
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
        </>
    );
}
