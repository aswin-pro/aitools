import { type BreadcrumbItem, type SharedData } from "@/types";
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

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("admin.dashboard"),
    },
    {
        title: "Settings",
        href: route("admin.index.account"),
    },
    {
        title: "System Settings",
        href: "#",
    },
];

export default function SystemSetting() {
    const {
        flash,
        config,
        timezonelist,
        currencies,
        dateTimeFormats,
        defaultLanguage,
        languages,
        selectedLanguages,
        image_limit,
    } = usePage<SharedData>().props;

    const showWebsite = config.find(
        (item) => item.config_key === "show_website",
    )?.config_value;

    const timeZone = config.find(
        (item) => item.config_key === "timezone",
    )?.config_value;

    const currency = config.find(
        (item) => item.config_key === "currency",
    )?.config_value;

    const currencyFormat = config.find(
        (item) => item.config_key === "currency_format_type",
    )?.config_value;

    const currencyDecimals = config.find(
        (item) => item.config_key === "currency_decimals_place",
    )?.config_value;

    const dateTimeFormat = config.find(
        (item) => item.config_key === "date_time_format",
    )?.config_value;

    const term = config.find(
        (item) => item.config_key === "term",
    )?.config_value;

    const [websiteVisibility, setWebsiteVisibility] = useState(
        showWebsite || "yes",
    );

    const [timeZoneValue, setTimeZoneValue] = useState(timeZone || "");

    const [currencyValue, setCurrencyValue] = useState(currency || "");

    const [currencyFormatValue, setCurrencyFormatValue] = useState(
        currencyFormat || "",
    );

    const [currencyDecimalValue, setcurrencyDecimalValue] = useState(
        currencyDecimals || 0,
    );

    const [dateTimeFormatValue, setDateTimeFormatValue] = useState(
        dateTimeFormat || "",
    );

    const [defaultLanguageValue, setDefaultLanguage] = useState(
        String(defaultLanguage || ""),
    );

    const [selectedLanguageValues, setSelectedLanguageValues] = useState<
        string[]
    >(selectedLanguages || []);

    const [termValue, setTermValue] = useState(term || "");

    const [imageLimitValue, setImageLimitValue] = useState(
        image_limit?.SIZE_LIMIT || "",
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="System Settings" />

            <SettingsLayout >
                <div className="space-y-6 max-w-[5xl]">
                    <HeadingSmall
                        title="System Settings"
                        description="General Website configuration settings"
                    />

                    <Form
                        action={route("admin.change.general.settings")}
                        method="post"
                        resetOnSuccess={false}
                        className="space-y-6"
                        onSuccess={() => {
                            toast.success(
                                "General Settings Updated Successfully!",
                            );
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
                                        label="Show Website Frontend?"
                                        value={websiteVisibility}
                                        onChange={setWebsiteVisibility}
                                        options={[
                                            { value: "yes", label: "Yes" },
                                            { value: "no", label: "No" },
                                        ]}
                                        placeholder="Select an option"
                                        name="show_website"
                                        error={errors.show_website}
                                        searchable={false}
                                    />

                                    <SearchableSelect
                                        label="Timezone"
                                        value={timeZoneValue}
                                        onChange={setTimeZoneValue}
                                        options={timezonelist.map((zone) => ({
                                            value: zone,
                                            label: zone,
                                        }))}
                                        placeholder="Select timezone"
                                        searchPlaceholder="Search timezone..."
                                        name="timezone"
                                        error={errors.timezone}
                                    />

                                    <SearchableSelect
                                        label="Currency"
                                        value={currencyValue}
                                        onChange={setCurrencyValue}
                                        options={currencies.map((currency) => ({
                                            value: currency.iso_code,
                                            label: `${currency.name} (${currency.symbol})`,
                                        }))}
                                        placeholder="Select currency"
                                        searchPlaceholder="Search currency..."
                                        name="currency"
                                        error={errors.currency}
                                    />

                                    <SearchableSelect
                                        label="Currency Format"
                                        value={currencyFormatValue}
                                        onChange={setCurrencyFormatValue}
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
                                        placeholder="Select currency format"
                                        searchPlaceholder="Search currency format..."
                                        emptyMessage="No currency format found."
                                        name="currency_format_type"
                                        error={errors.currency_format_type}
                                    />

                                    <div className="grid gap-2">
                                        <Label
                                            htmlFor="currency_decimals_place"
                                            required
                                        >
                                            Decimal Places
                                        </Label>

                                        <Input
                                            type="number"
                                            name="currency_decimals_place"
                                            value={currencyDecimalValue || 0}
                                            min={0}
                                            onChange={(e) =>
                                                setcurrencyDecimalValue(
                                                    e.target.value,
                                                )
                                            }
                                            placeholder="Decimals Places eg: 2, 3"
                                        />

                                        <InputError
                                            message={
                                                errors.currency_decimals_place
                                            }
                                        />
                                    </div>

                                    <SearchableSelect
                                        label="Date Time Format"
                                        value={dateTimeFormatValue}
                                        onChange={setDateTimeFormatValue}
                                        options={Object.entries(
                                            dateTimeFormats,
                                        ).map(([value, label]) => ({
                                            value,
                                            label,
                                        }))}
                                        placeholder="Select Date & Time format"
                                        searchPlaceholder="Search date & time format..."
                                        emptyMessage="No date & time format found."
                                        name="date_time_format"
                                        error={errors.date_time_format}
                                    />

                                    <div className="grid gap-2 md:col-span-3">
                                        <Label htmlFor="languages" required>
                                            Languages
                                        </Label>

                                        <LanguageMultiSelect
                                            languages={languages}
                                            value={selectedLanguageValues}
                                            onChange={setSelectedLanguageValues}
                                        />

                                        {selectedLanguageValues.map(
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
                                        label="Default Language"
                                        value={defaultLanguageValue}
                                        onChange={setDefaultLanguage}
                                        options={Object.entries(languages).map(
                                            ([code, name]) => ({
                                                value: code,
                                                label: name,
                                                searchValue: `${code} ${name}`,
                                            }),
                                        )}
                                        placeholder="Select default language"
                                        searchPlaceholder="Search language..."
                                        emptyMessage="No language found."
                                        name="default_language"
                                        error={errors.default_language}
                                    />

                                    <SearchableSelect
                                        label="Default Plan Term"
                                        value={termValue}
                                        onChange={setTermValue}
                                        options={[
                                            {
                                                value: "monthly",
                                                label: "Monthly",
                                            },
                                            {
                                                value: "yearly",
                                                label: "Yearly",
                                            },
                                        ]}
                                        placeholder="Select an option"
                                        name="term"
                                        error={errors.term}
                                        searchable={false}
                                    />

                                    {/* <div className="grid gap-2">
                                        <Label htmlFor="image_limit" required>
                                            Default Image Upload Size
                                        </Label>

                                        <Input
                                            id="image_limit"
                                            name="image_limit"
                                            type="number"
                                            value={imageLimitValue}
                                            onChange={(e) =>
                                                setImageLimitValue(
                                                    e.target.value,
                                                )
                                            }
                                            placeholder="Enter image size"
                                        />

                                        <InputError
                                            message={errors.image_limit}
                                        />
                                    </div> */}
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
