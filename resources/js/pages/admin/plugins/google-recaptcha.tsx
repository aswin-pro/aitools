import { App, Form, Head, usePage } from "@inertiajs/react";
import { toast } from "sonner";
import { useTranslation } from "react-i18next";

import FormInput from "@/components/admin/form-input";
import { SearchableSelect } from "@/components/admin/searchable-select";
import { Button } from "@/components/ui/button";
import { LoadingSwap } from "@/components/ui/loading-swap";
import AppLayout from "@/layouts/app/app-layout";
import { BreadcrumbItem } from "@/types";
import { useState } from "react";

type RecaptchaConfiguration = {
    RECAPTCHA_ENABLE: string;
    RECAPTCHA_SITE_KEY: string;
    RECAPTCHA_SECRET_KEY: string;
};

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
        title: "Google reCAPTCHA",
        href: "#",
    },
];

type PageProps = {
    recaptchaConfiguration: RecaptchaConfiguration;
};

export default function GoogleRecaptcha() {
    const { recaptchaConfiguration } = usePage<PageProps>().props;

    const [recaptchaEnable, setRecaptcha] = useState(
        recaptchaConfiguration.RECAPTCHA_ENABLE || "off",
    );

    const { t } = useTranslation();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Google reCAPTCHA Settings")} />

            <div className="space-y-6">
                <div>
                    <p className="text-sm text-muted-foreground">
                        {t("Overview")}
                    </p>

                    <h1 className="text-2xl font-semibold tracking-tight">
                        {t("Google reCAPTCHA Settings")}
                    </h1>

                 
                </div>

                {/* Form */}
                <div className="rounded-xl border bg-card">
                    <div className="border-b px-6 py-5">
                        <h2 className="font-semibold">
                            {t("Google reCAPTCHA Credentials")}
                        </h2>

                        <p className="mt-1 text-sm text-muted-foreground">
                            {t("Manage your Google reCAPTCHA configuration.")}
                        </p>
                    </div>

                    <Form
                        action={route("admin.google_recaptcha_settings.update")}
                        method="post"
                        className="p-6"
                        onSuccess={() => {
                            toast.success(
                                t(
                                    "Google reCAPTCHA settings updated successfully!",
                                ),
                            );
                        }}
                        onError={() => {
                            toast.error(
                                t("Error updating Google reCAPTCHA settings"),
                            );
                        }}
                    >
                        {({ errors, processing, clearErrors }) => (
                            <>
                                <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <SearchableSelect
                                        label={t("Google reCAPTCHA")}
                                        value={
                                            recaptchaEnable
                                        }
                                        onChange={(value) => {
                                            setRecaptcha(value);
                                            clearErrors("recaptcha_enable");
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
                                            "Select reCAPTCHA status",
                                        )}
                                        searchable={false}
                                        name="recaptcha_enable"
                                        error={errors.recaptcha_enable}
                                    />

                                    <FormInput
                                        id="recaptcha_site_key"
                                        name="recaptcha_site_key"
                                        type="text"
                                        label={t("Google reCAPTCHA Site Key")}
                                        defaultValue={
                                            recaptchaConfiguration.RECAPTCHA_SITE_KEY
                                        }
                                        placeholder={t(
                                            "Google reCAPTCHA Site Key",
                                        )}
                                        error={errors.recaptcha_site_key}
                                        onChange={() =>
                                            clearErrors("recaptcha_site_key")
                                        }
                                    />

                                    <FormInput
                                        id="recaptcha_secret_key"
                                        name="recaptcha_secret_key"
                                        type="text"
                                        label={t("Google reCAPTCHA Secret Key")}
                                        defaultValue={
                                            recaptchaConfiguration.RECAPTCHA_SECRET_KEY
                                        }
                                        placeholder={t(
                                            "Google reCAPTCHA Secret Key",
                                        )}
                                        error={errors.recaptcha_secret_key}
                                        onChange={() =>
                                            clearErrors("recaptcha_secret_key")
                                        }
                                    />
                                </div>

                                <div className="mt-6 flex justify-end border-t pt-6">
                                    <Button type="submit" disabled={processing}>
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
    );
}
