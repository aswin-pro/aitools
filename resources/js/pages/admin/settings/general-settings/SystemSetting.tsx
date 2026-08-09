import { type BreadcrumbItem, type SharedData } from "@/types";
import { Form, Head, usePage } from "@inertiajs/react";
import { useEffect, useRef, useState } from "react";
import HeadingSmall from "@/components/heading-small";
import InputError from "@/components/input-error";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import AppLayout from "@/layouts/app-layout";
import SettingsLayout from "@/layouts/settings/layout";
import { toast } from "sonner";
import { generalSettingNav } from "@/config/admin/general-setting-nav";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("admin.dashboard"),
    },
    {
        title: "General Settings",
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
    } = usePage<SharedData>().props ;

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

    useEffect(() => {
        if (flash.success) toast.success(flash.success);
        if (flash.error) toast.error(flash.error);
    }, [flash]);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Profile Settings" />

            <SettingsLayout items={generalSettingNav}>
                <div className="space-y-6 max-w-[5xl]">
                    <HeadingSmall
                        title="System Settings"
                        description="General Website configuration settings"
                    />

                    <Form
                        action={route("admin.change.general.settings")}
                        method="post"
                        encType="multipart/form-data"
                        resetOnSuccess={false}
                        className="space-y-6"
                    >
                        {({
                            errors,
                            processing,
                            recentlySuccessful,
                            clearErrors,
                            setError,
                        }) => (
                            <div className="space-y-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div className="grid gap-2">
                                    <Label
                                        htmlFor="website_visibility"
                                        required
                                    >
                                        Show Website Frontend?
                                    </Label>

                                    <Select
                                        value={websiteVisibility}
                                        onValueChange={setWebsiteVisibility}
                                    >
                                        <SelectTrigger className="w-full">
                                            <SelectValue placeholder="Select an option" />
                                        </SelectTrigger>

                                        <SelectContent>
                                            <SelectItem value="yes">
                                                Yes
                                            </SelectItem>
                                            <SelectItem value="no">
                                                No
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <input
                                        type="hidden"
                                        name="show_website"
                                        value={websiteVisibility}
                                    />

                                    <InputError message={errors.show_website} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="timezone" required>
                                        Timezone
                                    </Label>

                                    <Select
                                        value={timeZoneValue}
                                        onValueChange={setTimeZoneValue}
                                    >
                                        <SelectTrigger className="w-full">
                                            <SelectValue placeholder="Select an option" />
                                        </SelectTrigger>

                                        <SelectContent className="max-h-52">
                                            {timezonelist.map((zone) => (
                                                <SelectItem
                                                    key={zone}
                                                    value={zone}
                                                >
                                                    {zone}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>

                                    <input
                                        type="hidden"
                                        name="timezone"
                                        value={timeZoneValue}
                                    />

                                    <InputError message={errors.timezone} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="currency" required>
                                        Currency
                                    </Label>

                                    <Select
                                        value={currencyValue}
                                        onValueChange={setCurrencyValue}
                                    >
                                        <SelectTrigger className="w-full">
                                            <SelectValue placeholder="Select an option" />
                                        </SelectTrigger>

                                        <SelectContent className="max-h-50">
                                            {currencies.map((currency) => (
                                                <SelectItem
                                                    key={currency.id}
                                                    value={currency.iso_code}
                                                >
                                                    {currency.name} (
                                                    {currency.symbol})
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>

                                    <input
                                        type="hidden"
                                        name="currency"
                                        value={currencyValue}
                                    />

                                    <InputError
                                        message={errors.currencyValue}
                                    />
                                </div>

                                <div className="grid gap-2">
                                    <Label
                                        htmlFor="currency_format_type"
                                        required
                                    >
                                        Currency Format
                                    </Label>

                                    <Select
                                        value={currencyFormatValue}
                                        onValueChange={setCurrencyFormatValue}
                                    >
                                        <SelectTrigger className="w-full">
                                            <SelectValue placeholder="Select currency format" />
                                        </SelectTrigger>

                                        <SelectContent>
                                            <SelectItem value="1,234,567.89">
                                                1,234,567.89
                                            </SelectItem>

                                            <SelectItem value="12,34,567.89">
                                                12,34,567.89
                                            </SelectItem>

                                            <SelectItem value="1.234.567,89">
                                                1.234.567,89
                                            </SelectItem>

                                            <SelectItem value="1 234 567,89">
                                                1 234 567,89
                                            </SelectItem>

                                            <SelectItem value="1'234'567.89">
                                                1'234'567.89
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>

                                    <input
                                        type="hidden"
                                        name="currency_format_type"
                                        value={currencyFormatValue}
                                    />

                                    <InputError
                                        message={errors.currency_format_type}
                                    />
                                </div>

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
                                        message={errors.currency_decimals_place}
                                    />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="date_time_format" required>
                                        Date Time Format
                                    </Label>

                                    <Select
                                        value={dateTimeFormatValue}
                                        onValueChange={setDateTimeFormatValue}
                                    >
                                        <SelectTrigger className="w-full">
                                            <SelectValue placeholder="Select Date & Time format" />
                                        </SelectTrigger>

                                        <SelectContent className="max-h-52">
                                            {Object.entries(
                                                dateTimeFormats,
                                            ).map(([value, label]) => (
                                                <SelectItem
                                                    key={value}
                                                    value={value}
                                                >
                                                    {label}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>

                                    <input
                                        type="hidden"
                                        name="date_time_format"
                                        value={dateTimeFormatValue}
                                    />

                                    <InputError
                                        message={errors.date_time_format}
                                    />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="currency" required>
                                        Default Language
                                    </Label>
                                    <Select
                                        value={defaultLanguageValue}
                                        onValueChange={setDefaultLanguage}
                                    >
                                        <SelectTrigger className="w-full">
                                            <SelectValue placeholder="Select default language" />
                                        </SelectTrigger>

                                        <SelectContent className="max-h-50">
                                            {Object.entries(languages).map(
                                                ([code, name]) => (
                                                    <SelectItem
                                                        key={code}
                                                        value={code}
                                                    >
                                                        {name}
                                                    </SelectItem>
                                                ),
                                            )}
                                        </SelectContent>
                                    </Select>

                                    <input
                                        type="hidden"
                                        name="default_language"
                                        value={defaultLanguageValue}
                                    />

                                    <InputError
                                        message={errors.default_language}
                                    />
                                </div>

                                <div className="flex items-center gap-4">
                                    <Button disabled={processing}>
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
