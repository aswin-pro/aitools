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
import { LanguageMultiSelect } from "@/components/language-multi-select";


import { Check, ChevronsUpDown } from "lucide-react";
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from "@/components/ui/command";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/ui/popover";
import { cn } from "@/lib/utils";
import { SearchableSelect } from "@/components/searchable-select";

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
        selectedLanguages,
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

    const [selectedLanguageValues, setSelectedLanguageValues] =
        useState<string[]>(selectedLanguages || []);

    console.log("Selected languages: ", selectedLanguageValues);    

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
                            <div>
                            <div className="grid grid-cols-1 gap-3 md:grid-cols-3 items-start">

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
                                                                    message={errors.currency_decimals_place}
                                                                />
                                                            </div>


                            <SearchableSelect
                                value={dateTimeFormatValue}
                                onChange={setDateTimeFormatValue}
                                options={Object.entries(dateTimeFormats).map(
                                    ([value, label]) => ({
                                        value,
                                        label,
                                        searchValue: `${value} ${label}`,
                                    }),
                                )}
                                placeholder="Select Date & Time format"
                                searchPlaceholder="Search date & time format..."
                                emptyMessage="No date & time format found."
                                name="date_time_format"
                                error={errors.date_time_format}
                            />   

                            <SearchableSelect
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

                            </div>
                           
                            {/* <div className="space-y-4 grid grid-cols-1 md:grid-cols-3 items-start gap-3">
                                <div className="grid gap-2">
                                    <Label htmlFor="website_visibility" required>
                                        Show Website Frontend?
                                    </Label>

                                    <Popover>
                                        <PopoverTrigger asChild>
                                            <Button
                                                type="button"
                                                variant="outline"
                                                role="combobox"
                                                className="w-full justify-between font-normal"
                                            >
                                                {websiteVisibility === "yes"
                                                    ? "Yes"
                                                    : websiteVisibility === "no"
                                                    ? "No"
                                                    : "Select an option"}

                                                <ChevronsUpDown className="ml-2 h-4 w-4 shrink-0 opacity-50" />
                                            </Button>
                                        </PopoverTrigger>

                                        <PopoverContent
                                            className="w-[var(--radix-popover-trigger-width)] p-0"
                                            align="start"
                                        >
                                            <Command>
                                                <CommandList>
                                                    <CommandGroup>
                                                        <CommandItem
                                                            value="yes"
                                                            onSelect={() => {
                                                                setWebsiteVisibility("yes");
                                                            }}
                                                        >
                                                            <Check
                                                                className={cn(
                                                                    "mr-2 h-4 w-4",
                                                                    websiteVisibility === "yes"
                                                                        ? "opacity-100"
                                                                        : "opacity-0",
                                                                )}
                                                            />
                                                            Yes
                                                        </CommandItem>

                                                        <CommandItem
                                                            value="no"
                                                            onSelect={() => {
                                                                setWebsiteVisibility("no");
                                                            }}
                                                        >
                                                            <Check
                                                                className={cn(
                                                                    "mr-2 h-4 w-4",
                                                                    websiteVisibility === "no"
                                                                        ? "opacity-100"
                                                                        : "opacity-0",
                                                                )}
                                                            />
                                                            No
                                                        </CommandItem>
                                                    </CommandGroup>
                                                </CommandList>
                                            </Command>
                                        </PopoverContent>
                                    </Popover>

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

                                    <Popover>
                                        <PopoverTrigger asChild>
                                            <Button
                                                type="button"
                                                variant="outline"
                                                role="combobox"
                                                className="w-full justify-between font-normal"
                                            >
                                                <span className="truncate">
                                                    {timeZoneValue || "Select timezone"}
                                                </span>

                                                <ChevronsUpDown className="ml-2 h-4 w-4 shrink-0 opacity-50" />
                                            </Button>
                                        </PopoverTrigger>

                                        <PopoverContent
                                            className="w-[var(--radix-popover-trigger-width)] p-0"
                                            align="start"
                                        >
                                            <Command>
                                                <CommandInput placeholder="Search timezone..." />

                                                <CommandList>
                                                    <CommandEmpty>
                                                        No timezone found.
                                                    </CommandEmpty>

                                                    <CommandGroup>
                                                        {timezonelist.map((zone) => (
                                                            <CommandItem
                                                                key={zone}
                                                                value={zone}
                                                                onSelect={() => {
                                                                    setTimeZoneValue(zone);
                                                                }}
                                                            >
                                                                <Check
                                                                    className={cn(
                                                                        "mr-2 h-4 w-4",
                                                                        timeZoneValue === zone
                                                                            ? "opacity-100"
                                                                            : "opacity-0",
                                                                    )}
                                                                />

                                                                {zone}
                                                            </CommandItem>
                                                        ))}
                                                    </CommandGroup>
                                                </CommandList>
                                            </Command>
                                        </PopoverContent>
                                    </Popover>

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

                                    <Popover>
                                            <PopoverTrigger asChild>
                                                <Button
                                                    type="button"
                                                    variant="outline"
                                                    role="combobox"
                                                    className="w-full justify-between font-normal"
                                                >
                                                    <span className="truncate">
                                                        {currencyValue
                                                            ? currencies.find(
                                                                (currency) =>
                                                                    currency.iso_code === currencyValue,
                                                            )?.name +
                                                            ` (${currencies.find(
                                                                (currency) =>
                                                                    currency.iso_code === currencyValue,
                                                            )?.symbol})`
                                                            : "Select currency"}
                                                    </span>

                                                    <ChevronsUpDown className="ml-2 h-4 w-4 shrink-0 opacity-50" />
                                                </Button>
                                            </PopoverTrigger>

                                            <PopoverContent
                                                className="w-[var(--radix-popover-trigger-width)] p-0"
                                                align="start"
                                            >
                                                <Command>
                                                    <CommandInput placeholder="Search currency..." />

                                                    <CommandList>
                                                        <CommandEmpty>
                                                            No currency found.
                                                        </CommandEmpty>

                                                        <CommandGroup>
                                                            {currencies.map((currency) => (
                                                                <CommandItem
                                                                    key={currency.id}
                                                                    value={`${currency.name} ${currency.iso_code} ${currency.symbol}`}
                                                                    onSelect={() => {
                                                                        setCurrencyValue(
                                                                            currency.iso_code,
                                                                        );
                                                                    }}
                                                                >
                                                                    <Check
                                                                        className={cn(
                                                                            "mr-2 h-4 w-4",
                                                                            currencyValue ===
                                                                                currency.iso_code
                                                                                ? "opacity-100"
                                                                                : "opacity-0",
                                                                        )}
                                                                    />

                                                                    {currency.name} ({currency.symbol})
                                                                </CommandItem>
                                                            ))}
                                                        </CommandGroup>
                                                    </CommandList>
                                                </Command>
                                            </PopoverContent>
                                    </Popover>

                                    <input
                                        type="hidden"
                                        name="currency"
                                        value={currencyValue}
                                    />

                                    <InputError
                                        message={errors.currency}
                                    />
                                </div>

                                <div className="grid gap-2">
                                    <Label
                                        htmlFor="currency_format_type"
                                        required
                                    >
                                        Currency Format
                                    </Label>

                                    <Popover>
                                            <PopoverTrigger asChild>
                                                <Button
                                                    type="button"
                                                    variant="outline"
                                                    role="combobox"
                                                    className="w-full justify-between font-normal"
                                                >
                                                    <span className="truncate">
                                                        {currencyFormatValue || "Select currency format"}
                                                    </span>

                                                    <ChevronsUpDown className="ml-2 h-4 w-4 shrink-0 opacity-50" />
                                                </Button>
                                            </PopoverTrigger>

                                            <PopoverContent
                                                className="w-[var(--radix-popover-trigger-width)] p-0"
                                                align="start"
                                            >
                                                <Command>
                                                    <CommandInput placeholder="Search currency format..." />

                                                    <CommandList>
                                                        <CommandEmpty>
                                                            No currency format found.
                                                        </CommandEmpty>

                                                        <CommandGroup>
                                                            <CommandItem
                                                                value="1,234,567.89"
                                                                onSelect={() => {
                                                                    setCurrencyFormatValue("1,234,567.89");
                                                                }}
                                                            >
                                                                <Check
                                                                    className={cn(
                                                                        "mr-2 h-4 w-4",
                                                                        currencyFormatValue === "1,234,567.89"
                                                                            ? "opacity-100"
                                                                            : "opacity-0",
                                                                    )}
                                                                />

                                                                1,234,567.89
                                                            </CommandItem>

                                                            <CommandItem
                                                                value="12,34,567.89"
                                                                onSelect={() => {
                                                                    setCurrencyFormatValue("12,34,567.89");
                                                                }}
                                                            >
                                                                <Check
                                                                    className={cn(
                                                                        "mr-2 h-4 w-4",
                                                                        currencyFormatValue === "12,34,567.89"
                                                                            ? "opacity-100"
                                                                            : "opacity-0",
                                                                    )}
                                                                />

                                                                12,34,567.89
                                                            </CommandItem>

                                                            <CommandItem
                                                                value="1.234.567,89"
                                                                onSelect={() => {
                                                                    setCurrencyFormatValue("1.234.567,89");
                                                                }}
                                                            >
                                                                <Check
                                                                    className={cn(
                                                                        "mr-2 h-4 w-4",
                                                                        currencyFormatValue === "1.234.567,89"
                                                                            ? "opacity-100"
                                                                            : "opacity-0",
                                                                    )}
                                                                />

                                                                1.234.567,89
                                                            </CommandItem>

                                                            <CommandItem
                                                                value="1 234 567,89"
                                                                onSelect={() => {
                                                                    setCurrencyFormatValue("1 234 567,89");
                                                                }}
                                                            >
                                                                <Check
                                                                    className={cn(
                                                                        "mr-2 h-4 w-4",
                                                                        currencyFormatValue === "1 234 567,89"
                                                                            ? "opacity-100"
                                                                            : "opacity-0",
                                                                    )}
                                                                />

                                                                1 234 567,89
                                                            </CommandItem>

                                                            <CommandItem
                                                                value="1'234'567.89"
                                                                onSelect={() => {
                                                                    setCurrencyFormatValue("1'234'567.89");
                                                                }}
                                                            >
                                                                <Check
                                                                    className={cn(
                                                                        "mr-2 h-4 w-4",
                                                                        currencyFormatValue === "1'234'567.89"
                                                                            ? "opacity-100"
                                                                            : "opacity-0",
                                                                    )}
                                                                />

                                                                1'234'567.89
                                                            </CommandItem>
                                                        </CommandGroup>
                                                    </CommandList>
                                                </Command>
                                            </PopoverContent>
                                    </Popover>

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

                                    <Popover>
                                            <PopoverTrigger asChild>
                                                <Button
                                                    type="button"
                                                    variant="outline"
                                                    role="combobox"
                                                    className="w-full justify-between font-normal"
                                                >
                                                    <span className="truncate">
                                                        {dateTimeFormatValue ||
                                                            "Select Date & Time format"}
                                                    </span>

                                                    <ChevronsUpDown className="ml-2 h-4 w-4 shrink-0 opacity-50" />
                                                </Button>
                                            </PopoverTrigger>

                                            <PopoverContent
                                                className="w-[var(--radix-popover-trigger-width)] p-0"
                                                align="start"
                                            >
                                                <Command>
                                                    <CommandInput placeholder="Search date & time format..." />

                                                    <CommandList>
                                                        <CommandEmpty>
                                                            No date & time format found.
                                                        </CommandEmpty>

                                                        <CommandGroup>
                                                            {Object.entries(dateTimeFormats).map(
                                                                ([value, label]) => (
                                                                    <CommandItem
                                                                        key={value}
                                                                        value={`${value} ${label}`}
                                                                        onSelect={() => {
                                                                            setDateTimeFormatValue(value);
                                                                        }}
                                                                    >
                                                                        <Check
                                                                            className={cn(
                                                                                "mr-2 h-4 w-4",
                                                                                dateTimeFormatValue === value
                                                                                    ? "opacity-100"
                                                                                    : "opacity-0",
                                                                            )}
                                                                        />

                                                                        {label}
                                                                    </CommandItem>
                                                                ),
                                                            )}
                                                        </CommandGroup>
                                                    </CommandList>
                                                </Command>
                                            </PopoverContent>
                                    </Popover>

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

                                    <Popover>
                                            <PopoverTrigger asChild>
                                                <Button
                                                    type="button"
                                                    variant="outline"
                                                    role="combobox"
                                                    className="w-full justify-between font-normal"
                                                >
                                                    <span className="truncate">
                                                        {languages[defaultLanguageValue] ||
                                                            "Select default language"}
                                                    </span>

                                                    <ChevronsUpDown className="ml-2 h-4 w-4 shrink-0 opacity-50" />
                                                </Button>
                                            </PopoverTrigger>

                                            <PopoverContent
                                                className="w-[var(--radix-popover-trigger-width)] p-0"
                                                align="start"
                                            >
                                                <Command>
                                                    <CommandInput placeholder="Search language..." />

                                                    <CommandList>
                                                        <CommandEmpty>
                                                            No language found.
                                                        </CommandEmpty>

                                                        <CommandGroup>
                                                            {Object.entries(languages).map(
                                                                ([code, name]) => (
                                                                    <CommandItem
                                                                        key={code}
                                                                        value={`${code} ${name}`}
                                                                        onSelect={() => {
                                                                            setDefaultLanguage(code);
                                                                        }}
                                                                    >
                                                                        <Check
                                                                            className={cn(
                                                                                "mr-2 h-4 w-4",
                                                                                defaultLanguageValue === code
                                                                                    ? "opacity-100"
                                                                                    : "opacity-0",
                                                                            )}
                                                                        />

                                                                        {name}
                                                                    </CommandItem>
                                                                ),
                                                            )}
                                                        </CommandGroup>
                                                    </CommandList>
                                                </Command>
                                            </PopoverContent>
                                    </Popover>


                                    <input
                                        type="hidden"
                                        name="default_language"
                                        value={defaultLanguageValue}
                                    />

                                    <InputError
                                        message={errors.default_language}
                                    />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="languages" required>
                                        Languages
                                    </Label>

                                    <LanguageMultiSelect
                                        languages={languages}
                                        value={selectedLanguageValues}
                                        onChange={setSelectedLanguageValues}
                                    />

                                    <InputError message={errors.languages} />
                                </div>


                            </div> */}
                                <div className="flex items-center mt-4 gap-4">
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
