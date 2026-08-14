import { type BreadcrumbItem, type NavItem } from "@/types";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { Label } from "@/components/ui/label";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import { cn } from "@/lib/utils";
import { Check } from "lucide-react";
import { useState } from "react";
import SettingsLayout from "@/layouts/settings/layout";
import AppLayout from "@/layouts/app/app-layout";
import HeadingSmall from "@/components/heading-small";
import { useTranslation } from "react-i18next";
import { assetUrl } from "@/helpers/asset-url";
import { Form } from "@inertiajs/react";
import { Button } from "@/components/ui/button";
import { themeColors } from "@/data/theme-color";
import FormInput from "@/components/admin/form-input";
import { toast } from "sonner";
import { LoadingSwap } from "@/components/ui/loading-swap";
import ThemeSelector from "./themeselector";
import ThemeColorSelector from "./theme-color-selector";
import WebsiteBrandingFields from "./input-fields";

type Theme = {
    theme_id: string | number;
    theme_name: string;
    cover_image: string;
};

type WebsiteSettingsProps = {
    themes: Theme[];
    config: any;
    settings: any;
    appName: string;
    customizableThemeIds: string[];
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
    themes,
    customizableThemeIds,
    config,
    settings,
    appName,
}: WebsiteSettingsProps) {
    const currentTheme =
        config?.find?.((item: any) => item.config_key === "default_theme")
            ?.config_value ?? "";

    const currentThemeColor =
        config.find((item: any) => item.config_key === "app_theme")
            ?.config_value ?? "";

    const [themeValue, setThemeValue] = useState(String(currentTheme));

    const [themeColor, setThemeColor] = useState(currentThemeColor);

    const selectedTheme = themes.find(
        (theme) => String(theme.theme_id) === themeValue, // currently seclected theme like model or classic
    );

    const showThemeCustomization = customizableThemeIds.includes(
        String(selectedTheme?.theme_id),
    );

    const { t } = useTranslation();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <SettingsLayout>
                <Form
                    action={route("dashboard.admin.change.website.settings")}
                    method="post"
                    encType="multipart/form-data"
                    options={{ preserveScroll: true }}
                    onSuccess={() => {
                        toast.success(
                            t("Website Settings Updated Successfully!"),
                        );
                    }}
                    onError={() => {
                        toast.error(t("Error updating website settings"));
                    }}
                >
                    {({
                        errors,
                        processing,
                        recentlySuccessful,
                        clearErrors,
                        setError,
                    }) => {
                        return (
                            <div className="space-y-8">
                                <HeadingSmall
                                    title={t("Website Configuration")}
                                    description={t(
                                        "Customize your website appearance and branding.",
                                    )}
                                />

                                {/* Appearance */}
                                <Card>
                                    <CardHeader className="border-b">
                                        <div>
                                            <h2 className="text-base font-semibold">
                                                {t("Appearance")}
                                            </h2>

                                            <p className="mt-1 text-sm text-muted-foreground">
                                                {t(
                                                    "Choose the theme you want to use for your website.",
                                                )}
                                            </p>
                                        </div>
                                    </CardHeader>

                                    <CardContent className="pt-6">
                                        <ThemeSelector
                                            themes={themes}
                                            value={themeValue}
                                            onChange={setThemeValue}
                                        />

                                        {showThemeCustomization && (
                                            <ThemeColorSelector
                                                value={themeColor}
                                                onChange={setThemeColor}
                                            />
                                        )}

                                        <WebsiteBrandingFields
                                            appName={appName}
                                            settings={settings}
                                            errors={errors}
                                            clearErrors={clearErrors}
                                            showThemeCustomization={
                                                showThemeCustomization
                                            }
                                        />
                                    </CardContent>
                                </Card>

                                {/* Submit */}
                                <div className="flex justify-end">
                                    <Button type="submit">
                                        <LoadingSwap isLoading={processing}>
                                            {t("Update")}
                                        </LoadingSwap>
                                    </Button>
                                </div>
                            </div>
                        );
                    }}
                </Form>
            </SettingsLayout>
        </AppLayout>
    );
}
