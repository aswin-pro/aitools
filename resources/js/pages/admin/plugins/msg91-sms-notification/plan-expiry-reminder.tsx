import { useForm } from "@inertiajs/react";
import { useTranslation } from "react-i18next";

import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { Switch } from "@/components/ui/switch";

import { shortcodes } from "./shortcodes";
import FormInput from "@/components/admin/form-input";
import ShortcodeTable from "./short-code-table";
import { LoadingSwap } from "@/components/ui/loading-swap";
import { toast } from "sonner";

interface MSG91Template {
    id: number;
    template_name: string;
    template_id: string | null;
    is_enabled: number;
}

interface Props {
    template: MSG91Template | undefined;
}

export default function PlanExpiryReminder({ template }: Props) {
    const { t } = useTranslation();

    const form = useForm({
        user_plan_expiry_remainder: template?.is_enabled === 1,
        user_plan_expiry_remainder_template_id:
            template?.template_id ?? "",
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();

        form.post(
            route(
                "admin.msg91_sms_template_plan_expiry_remainder.update",
            ),
            {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success(
                        t("Template updated successfully."),
                    );
                },

                onError: () => {
                    toast.error(
                        t("Error updating template"),
                    );
                },
            },
        );
    };

    return (
        <section className="space-y-6 ">
            <div>
                <h2 className="text-lg font-semibold">
                    {t("Plan Expiry Reminder")}
                </h2>

                <p className="text-sm text-muted-foreground">
                    {t(
                        "Configure SMS reminders before a user's plan expires.",
                    )}
                </p>
            </div>

            <div className="rounded-md border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:border-blue-900 dark:bg-blue-950/30 dark:text-blue-300">
                {t(
                    "Note: You need to specify the date and time in the cron job to send notifications. (Settings → Cron Jobs)",
                )}
            </div>

            <form onSubmit={submit} className="space-y-6">
                <div className="grid gap-6 md:grid-cols-2 items-start">
                    <div className="grid gap-2">
                        <Label htmlFor="user_plan_expiry_remainder">
                            {t("Send Notification to User")}
                        </Label>

                        <div className="flex h-10 items-center">
                            <Switch
                                id="user_plan_expiry_remainder"
                                checked={
                                    form.data.user_plan_expiry_remainder
                                }
                                onCheckedChange={(checked) => {
                                    form.setData(
                                        "user_plan_expiry_remainder",
                                        checked,
                                    );

                                    form.clearErrors(
                                        "user_plan_expiry_remainder_template_id",
                                    );
                                }}
                            />
                        </div>
                    </div>

                    <FormInput
                        id="user_plan_expiry_remainder_template_id"
                        name="user_plan_expiry_remainder_template_id"
                        type="text"
                        label={t("User Template ID")}
                        placeholder={t("User Template ID")}
                        value={
                            form.data
                                .user_plan_expiry_remainder_template_id
                        }
                        error={
                            form.errors
                                .user_plan_expiry_remainder_template_id
                        }
                        onChange={(e) => {
                            form.setData(
                                "user_plan_expiry_remainder_template_id",
                                e.target.value,
                            );

                            form.clearErrors(
                                "user_plan_expiry_remainder_template_id",
                            );
                        }}
                    />
                </div>

                <ShortcodeTable
                    shortcodes={shortcodes.plan_expiry}
                />

                <div className="flex justify-end">
                    <Button
                        type="submit"
                        disabled={form.processing}
                    >
                        <LoadingSwap isLoading={form.processing}>
                            {t("Update")}
                        </LoadingSwap>
                    </Button>
                </div>
            </form>
        </section>
    );
}