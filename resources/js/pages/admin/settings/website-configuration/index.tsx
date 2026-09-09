import { type BreadcrumbItem } from "@/types";
import AppLayout from "@/layouts/app/app-layout";
import SettingsLayout from "@/layouts/settings/layout";
import HeadingSmall from "@/components/heading-small";
import { useTranslation } from "react-i18next";
import { Form } from "@inertiajs/react";
import { Button } from "@/components/ui/button";
import { toast } from "sonner";
import { LoadingSwap } from "@/components/ui/loading-swap";
import WebsiteBrandingFields from "./input-fields";


type WebsiteSettingsProps = {
    config: any;
    settings: any;
    appName: string;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Settings",
        href: route("dashboard.admin.edit.account"),
    },
    {
        title: "Website Settings",
        href: "#",
    },
];

export default function WebsiteSettings({
    config,
    settings,
    appName,
}: WebsiteSettingsProps) {
    const { t } = useTranslation();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <SettingsLayout>
                <Form
                    action={route(
                        "dashboard.admin.change.website.settings"
                    )}
                    method="post"
                    encType="multipart/form-data"
                    options={{ preserveScroll: true }}
                    onSuccess={() => {
                        toast.success(
                            t("Website Settings Updated Successfully!")
                        );
                    }}
                    onError={(errors) => {
                            console.log("Website settings errors:", errors);

                        toast.error(
                            t("Error updating website settings")
                        );
                    }}
                >
                    {({
                        errors,
                        processing,
                        clearErrors,
                    }) => (
                        <div className="space-y-8">
                            <HeadingSmall
                            t={t}
                                title={t("Website Configuration")}
                                description={t(
                                    "Customize your website appearance and branding."
                                )}
                            />

                            <WebsiteBrandingFields
                                appName={appName}
                                settings={settings}
                                errors={errors}
                                clearErrors={clearErrors}
                            />

                            <div className="flex justify-end">
                                <Button
                                    type="submit"
                                    disabled={processing}
                                >
                                    <LoadingSwap isLoading={processing}>
                                        {t("Update")}
                                    </LoadingSwap>
                                </Button>
                            </div>
                        </div>
                    )}
                </Form>
            </SettingsLayout>
        </AppLayout>
    );
}