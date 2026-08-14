import { type BreadcrumbItem } from "@/types";
import { Form, Head, usePage } from "@inertiajs/react";
import { useState } from "react";
import { toast } from "sonner";
import { Copy, Clock3, Info, Save } from "lucide-react";
import AppLayout from "@/layouts/app/app-layout";
import SettingsLayout from "@/layouts/settings/layout";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Card, CardContent, CardHeader } from "@/components/ui/card";

import { LoadingSwap } from "@/components/ui/loading-swap";
import { useTranslation } from "react-i18next";
import { SearchableSelect } from "@/components/admin/searchable-select";
import FormInput from "@/components/admin/form-input";
import InformationCard from "./information-card";
import TestReminder from "./test-remainder";

type CronPageProps = {
    dates: string;
    cronHour: string;
    cronCommand: string;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Settings",
        href: route("dashboard.admin.index.account"),
    },
    {
        title: "Cron Jobs",
        href: "#",
    },
];

export default function Index() {
    const {
        dates: initialDates,
        cronHour: initialCronHour,
        cronCommand: initialCronCommand,
    } = usePage<CronPageProps>().props;

    const { t } = useTranslation();

    const [dates, setDates] = useState(initialDates || "");

    const [cronHour, setCronHour] = useState(String(initialCronHour || "0"));

    const [cronCommand, setCronCommand] = useState(initialCronCommand || "");

    const generateCronCommand = (hour: string) => {
        const command = cronCommand.replace(/^0 \d+ /, `0 ${hour} `);
        setCronCommand(command);
    };

    const handleHourChange = (event: React.ChangeEvent<HTMLSelectElement>) => {
        const hour = event.target.value;

        setCronHour(hour);

        generateCronCommand(hour);
    };

    const copyCronCommand = async () => {
        try {
            await navigator.clipboard.writeText(cronCommand);

            toast.success(t("CRON command copied!"));
        } catch {
            toast.error(t("Failed to copy CRON command."));
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Cron Jobs")} />

            <SettingsLayout>
                <div className="max-w-[5xl] space-y-6">
                    <Form
                        action={route("dashboard.admin.update.cron.jobs")}
                        method="post"
                        resetOnSuccess={false}
                        className="space-y-6"
                        options={{ preserveScroll: true }}
                        onSuccess={() => {
                            toast.success(
                                t("Cron job settings updated successfully!"),
                            );
                        }}
                        onError={() => {
                            toast.error(t("Please fix the errors below."));
                        }}
                    >
                        {({ errors, processing, clearErrors }) => (
                            <>
                                <Card>
                                    <CardHeader>
                                        <div className="flex items-center gap-3">
                                          

                                            <div>
                                                <h2 className="font-semibold">
                                                    {t("Cron Jobs")}
                                                </h2>

                                                <p className="text-sm text-muted-foreground">
                                                    {t(
                                                        "Configure reminder dates and the time when Laravel's scheduler should run.",
                                                    )}
                                                </p>
                                            </div>
                                        </div>
                                    </CardHeader>

                                    <CardContent className="space-y-6">
                                        <div className="grid gap-6 md:grid-cols-2 items-start">
                                            {/* Dates */}

                                            <FormInput
                                                id="dates_in_array"
                                                name="dates_in_array"
                                                type="text"
                                                label={t("Dates")}
                                                required
                                                value={dates}
                                                placeholder="-30,1,3,5,10,366"
                                                error={errors.dates_in_array}
                                                onChange={(event) => {
                                                    setDates(
                                                        event.target.value,
                                                    );
                                                    clearErrors(
                                                        "dates_in_array",
                                                    );
                                                }}
                                            />

                                            {/* Cron settings */}
                                            {/* Hour */}
                                            <SearchableSelect
                                                label={t("Hour")}
                                                value={cronHour}
                                                onChange={(value) => {
                                                    setCronHour(value);
                                                    generateCronCommand(value);
                                                    clearErrors("cron_hour");
                                                }}
                                                options={Array.from(
                                                    { length: 24 },
                                                    (_, hour) => ({
                                                        value: String(hour),
                                                        label: `${String(hour).padStart(2, "0")}:00`,
                                                    }),
                                                )}
                                                placeholder={t("Select hour")}
                                                name="cron_hour"
                                                error={errors.cron_hour}
                                                searchable={false}
                                            />
                                        </div>

                                        {/* CRON command */}
                                        <div className="space-y-2">
                                            <Label htmlFor="cron_command">
                                                {t("Your CRON Command")}
                                            </Label>

                                            <div className="flex gap-2">
                                                <Input
                                                    id="cron_command"
                                                    value={cronCommand}
                                                    readOnly
                                                    className="font-mono text-xs"
                                                />

                                                <Button
                                                    className="bg-primary text-white dark:text-black"
                                                    type="button"
                                                    variant="outline"
                                                    size="icon"
                                                    onClick={copyCronCommand}
                                                    title={t(
                                                        "Copy CRON command",
                                                    )}
                                                >
                                                    <Copy className="size-4" />
                                                </Button>
                                            </div>
                                        </div>
                                        {/* </div> */}
                                    </CardContent>

                                    {/* Footer */}

                                    <div className="flex items-center gap-3 border-t px-6 py-4">
                                        <TestReminder />
                                        <Button
                                            type="submit"
                                            disabled={processing}
                                        >
                                            <LoadingSwap isLoading={processing}>
                                                {t("Update")}
                                            </LoadingSwap>
                                        </Button>
                                    </div>
                                </Card>
                            </>
                        )}
                    </Form>
                    <InformationCard />
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
