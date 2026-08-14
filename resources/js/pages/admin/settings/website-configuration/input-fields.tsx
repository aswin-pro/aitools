import { useTranslation } from "react-i18next";
import FormInput from "@/components/admin/form-input";

interface WebsiteBrandingFieldsProps {
    appName: string;
    settings: any;
    errors: Record<string, string>;
    clearErrors: (field: string) => void;
    showThemeCustomization: boolean;
}

export default function WebsiteBrandingFields({
    appName,
    settings,
    errors,
    clearErrors,
    showThemeCustomization,
}: WebsiteBrandingFieldsProps) {
    const { t } = useTranslation();

    return (
        <div className="mt-6 grid grid-cols-1 items-start gap-6 md:grid-cols-2">
            <FormInput
                id="app_name"
                name="app_name"
                type="text"
                label={t("App Name")}
                required
                defaultValue={appName}
                placeholder={t("Enter app name")}
                error={errors.app_name}
                onChange={() => clearErrors("app_name")}
            />

            <FormInput
                id="site_name"
                name="site_name"
                type="text"
                label={t("Site Name")}
                required
                defaultValue={settings?.site_name ?? ""}
                placeholder={t("Enter site name")}
                error={errors.site_name}
                onChange={() => clearErrors("site_name")}
            />

            {showThemeCustomization && (
                <FormInput
                    id="primary_image"
                    name="primary_image"
                    type="file"
                    label={t("Banner Image")}
                    accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml"
                    error={errors.primary_image}
                    onChange={() => clearErrors("primary_image")}
                    subLable={t("Recommended size: 1000 × 667")}
                />
            )}

            <FormInput
                id="site_logo"
                name="site_logo"
                type="file"
                label={t("Website Logo")}
                accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml"
                error={errors.site_logo}
                onChange={() => clearErrors("site_logo")}
                subLable={t("Recommended size: 200 × 90")}
            />

            <FormInput
                id="site_logo_light"
                name="site_logo_light"
                type="file"
                label={t("Website Logo (Light)")}
                accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml"
                error={errors.site_logo_light}
                onChange={() => clearErrors("site_logo_light")}
                subLable={t("Recommended size: 200 × 90")}
            />

            <FormInput
                id="favi_icon"
                name="favi_icon"
                type="file"
                label={t("Favicon")}
                accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp"
                error={errors.favi_icon}
                onChange={() => clearErrors("favi_icon")}
                subLable={t("Recommended size: 32 × 32")}
            />
        </div>
    );
}