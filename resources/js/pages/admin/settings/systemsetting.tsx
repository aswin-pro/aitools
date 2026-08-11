import { type BreadcrumbItem } from "@/types";
import { Form, Head, usePage } from "@inertiajs/react";
import { useState } from "react";
import HeadingSmall from "@/components/heading-small";
import InputError from "@/components/input-error";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import SettingsLayout from "@/layouts/settings/layout";
import { toast } from "sonner";
import { SearchableSelect } from "@/components/admin/searchable-select";
import { LanguageMultiSelect } from "@/components/admin/language-multi-select";
import AppLayout from "@/layouts/app/app-layout";
import { useTranslation } from "react-i18next";
import FormInput from "@/components/admin/form-input";
import { systemSetting } from "@/types/admin";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.dashboard"),
    },
    {
        title: "Settings",
        href: route("dashboard.admin.index.account"),
    },
    {
        title: "System Settings",
        href: "#",
    },
];

export default function SystemSetting() {
    const {
        config,
        timezonelist,
        currencies,
        dateTimeFormats,
        defaultLanguage,
        languages,
        selectedLanguages,
        image_limit,
    } = usePage<systemSetting>().props;

    const configValues = Object.fromEntries(
        config.map((item) => [item.config_key, item.config_value]),
    );

    //accessing the config values
    configValues.show_website;
    configValues.timezone;
    configValues.currency;
    configValues.currency_format_type;
    configValues.currency_decimals_place;
    configValues.date_time_format;
    configValues.term;

    const [settings, setSettings] = useState({
        websiteVisibility: configValues.show_website || "yes",
        timezone: configValues.timezone || "",
        currency: configValues.currency || "",
        currencyFormat: configValues.currency_format_type || "",
        currencyDecimals: configValues.currency_decimals_place || 0,
        dateTimeFormat: configValues.date_time_format || "",
        defaultLanguage: String(defaultLanguage || ""),
        selectedLanguages: selectedLanguages || [],
        term: configValues.term || "",
    });

    const [imageLimitValue, setImageLimitValue] = useState(image_limit.SIZE_LIMIT);

    const updateSetting = (key: string, value: any) => {
        setSettings((prev) => ({
            ...prev,
            [key]: value,
        }));
    };

    const { t } = useTranslation();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("System Settings")} />

            <SettingsLayout>
                <div className="space-y-6 max-w-[5xl]">
                    <HeadingSmall
                        title={t("System Settings")}
                        description={"General Website configuration settings"}
                    />

                    <Form
                        action={route(
                            "dashboard.admin.change.general.settings",
                        )}
                        method="post"
                        resetOnSuccess={false}
                        className="space-y-6"
                        onSuccess={() => {
                            toast.success(t("Settings Updated Successfully!"));
                        }}
                        onError={() => {
                            toast.error(t("Error updating settings"));
                        }}
                    >
                        {({
                            errors,
                            processing,
                            recentlySuccessful,
                            clearErrors,
                            setError,
                        }) => (
                            <div>
                                <div className="grid grid-cols-1 gap-6 md:grid-cols-3 items-start">
                                    <SearchableSelect
                                        label={t("Show Website Frontend?")}
                                        value={settings.websiteVisibility}
                                        onChange={(value) =>
                                            updateSetting(
                                                "websiteVisibility",
                                                value,
                                            )
                                        }
                                        options={[
                                            { value: "yes", label: t("Yes") },
                                            { value: "no", label: t("No") },
                                        ]}
                                        placeholder={t("Select an option")}
                                        name="show_website"
                                        error={errors.show_website}
                                        searchable={false}
                                    />

                                    <SearchableSelect
                                        label={t("Timezone")}
                                        value={settings.timezone}
                                        onChange={(value) =>
                                            updateSetting("timezone", value)
                                        }
                                        options={timezonelist.map((zone) => ({
                                            value: zone,
                                            label: zone,
                                        }))}
                                        placeholder={t("Select timezone")}
                                        searchPlaceholder={t(
                                            "Search timezone...",
                                        )}
                                        name="timezone"
                                        error={errors.timezone}
                                    />

                                    <SearchableSelect
                                        label={t("Currency")}
                                        value={settings.currency}
                                        onChange={(value) =>
                                            updateSetting("currency", value)
                                        }
                                        options={currencies.map((currency) => ({
                                            value: currency.iso_code,
                                            label: `${currency.name} (${currency.symbol})`,
                                        }))}
                                        placeholder={t("Select currency")}
                                        searchPlaceholder={t(
                                            "Search currency...",
                                        )}
                                        name="currency"
                                        error={errors.currency}
                                    />

                                    <SearchableSelect
                                        label={t("Currency Format")}
                                        value={settings.currencyFormat}
                                        onChange={(value) =>
                                            updateSetting(
                                                "currencyFormat",
                                                value,
                                            )
                                        }
                                        options={[
                                            {
                                                value: "1,234,567.89",
                                                label: "1,234,567.89",
                                            },
                                            {
                                                value: "12,34,567.89",
                                                label: "12,34,567.89",
                                            },
                                            {
                                                value: "1.234.567,89",
                                                label: "1.234.567,89",
                                            },
                                            {
                                                value: "1 234 567,89",
                                                label: "1 234 567,89",
                                            },
                                            {
                                                value: "1'234'567.89",
                                                label: "1'234'567.89",
                                            },
                                        ]}
                                        placeholder={t(
                                            "Select currency format",
                                        )}
                                        searchPlaceholder={t(
                                            "Search currency format...",
                                        )}
                                        emptyMessage={t(
                                            "No currency format found.",
                                        )}
                                        name="currency_format_type"
                                        error={errors.currency_format_type}
                                    />

                                    <FormInput
                                        type="number"
                                        name="currency_decimals_place"
                                        label={t("Decimal Places")}
                                        value={settings.currencyDecimals || 0}
                                        min={0}
                                        onChange={(e) =>
                                            updateSetting(
                                                "currencyDecimals",
                                                e.target.value,
                                            )
                                        }
                                        placeholder={t(
                                            "Decimals Places eg: 2, 3",
                                        )}
                                        error={errors.currency_decimals_place}
                                    />

                                    <SearchableSelect
                                        label={t("Date Time Format")}
                                        value={settings.dateTimeFormat}
                                        onChange={(value) =>
                                            updateSetting(
                                                "dateTimeFormat",
                                                value,
                                            )
                                        }
                                        options={Object.entries(
                                            dateTimeFormats,
                                        ).map(([value, label]) => ({
                                            value,
                                            label,
                                        }))}
                                        placeholder={t(
                                            "Select Date & Time format",
                                        )}
                                        searchPlaceholder={t(
                                            "Search date & time format...",
                                        )}
                                        emptyMessage={t(
                                            "No date & time format found.",
                                        )}
                                        name="date_time_format"
                                        error={errors.date_time_format}
                                    />

                                    <div className="grid gap-2 md:col-span-3">
                                        <Label htmlFor="languages" required>
                                            {t("Languages")}
                                        </Label>

                                        <LanguageMultiSelect
                                            languages={languages}
                                            value={settings.selectedLanguages}
                                            onChange={(value) =>
                                                updateSetting(
                                                    "selectedLanguages",
                                                    value,
                                                )
                                            }
                                        />

                                        {settings.selectedLanguages.map(
                                            (language) => (
                                                <input
                                                    key={language}
                                                    type="hidden"
                                                    name="languages[]"
                                                    value={language}
                                                />
                                            ),
                                        )}

                                        <InputError
                                            message={errors.languages}
                                        />
                                    </div>

                                    <SearchableSelect
                                        label={t("Default Language")}
                                        value={settings.defaultLanguage}
                                        onChange={(value) =>
                                            updateSetting(
                                                "defaultLanguage",
                                                value,
                                            )
                                        }
                                        options={Object.entries(languages).map(
                                            ([code, name]) => ({
                                                value: code,
                                                label: name,
                                                searchValue: `${code} ${name}`,
                                            }),
                                        )}
                                        placeholder={t(
                                            "Select default language",
                                        )}
                                        searchPlaceholder={t(
                                            "Search language...",
                                        )}
                                        emptyMessage={t("No language found.")}
                                        name="default_language"
                                        error={errors.default_language}
                                    />

                                    <SearchableSelect
                                        label={t("Default Plan Term")}
                                        value={settings.term}
                                        onChange={(value) =>
                                            updateSetting("term", value)
                                        }
                                        options={[
                                            {
                                                value: "monthly",
                                                label: t("Monthly"),
                                            },
                                            {
                                                value: "yearly",
                                                label: t("Yearly"),
                                            },
                                        ]}
                                        placeholder={t("Select an option")}
                                        name="term"
                                        error={errors.term}
                                        searchable={false}
                                    />

                                    <FormInput
                                        id="image_limit"
                                        name="image_limit"
                                        type="number"
                                        label={t("Default Image Upload Size")}
                                        value={imageLimitValue}
                                        onChange={(e) =>
                                            setImageLimitValue(e.target.value)
                                        }
                                        placeholder={t("Enter image size")}
                                        error={errors.image_limit}
                                    />
                                </div>

                                <div className="flex items-center mt-4 gap-4">
                                    <Button type="submit" disabled={processing}>
                                        Update
                                    </Button>
                                </div>
                            </div>
                        )}
                    </Form>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
