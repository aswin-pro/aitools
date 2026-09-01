import { useForm } from "@inertiajs/react";

import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { Switch } from "@/components/ui/switch";

import { shortcodes } from "./shortcodes";
import FormInput from "@/components/admin/form-input";
import ShortcodeTable from "./short-code-table";
import { LoadingSwap } from "@/components/ui/loading-swap";
import { toast } from "sonner";

interface TwilioTemplate {
    id: number;
    template_name: string;
    template_sid: string | null;
    is_enabled: number;
}

interface Props {
    template: TwilioTemplate | undefined;
}

export default function PlanExpiryReminder({ template }: Props) {
    const form = useForm({
        user_plan_expiry_remainder: template?.is_enabled === 1,
        user_plan_expiry_remainder_template_sid:
            template?.template_sid ?? "",
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();

        form.post(
            route(
                "admin.twilio_whatsapp_template_plan_expiry_remainder.update",
            ),
            {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success(
                        "Template updated successfully.",
                    );
                },

                onError: () => {
                    toast.error(
                        "Error updating template",
                    );
                },
            },
        );
    };

    return (
        <section className="space-y-6">
            <div>
                <h2 className="text-lg font-semibold">
                    Plan Expiry Reminder
                </h2>

                <p className="text-sm text-muted-foreground">
                    Configure WhatsApp reminders before a user's plan expires.
                </p>
            </div>

            <div className="rounded-md border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:border-blue-900 dark:bg-blue-950/30 dark:text-blue-300">
                Note: You need to specify the date and time in the cron job
                to send notifications. (Settings → Cron Jobs)
            </div>

            <form onSubmit={submit} className="space-y-6">
                <div className="grid gap-6 md:grid-cols-2 items-start">
                    <div className="grid gap-2">
                        <Label htmlFor="user_plan_expiry_remainder">
                            Send Notification to User
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
                                        "user_plan_expiry_remainder_template_sid",
                                    );
                                }}
                            />
                        </div>
                    </div>

                    <FormInput
                        id="user_plan_expiry_remainder_template_sid"
                        name="user_plan_expiry_remainder_template_sid"
                        type="text"
                        label="User Template SID"
                        placeholder="User Template SID"
                        value={
                            form.data
                                .user_plan_expiry_remainder_template_sid
                        }
                        error={
                            form.errors
                                .user_plan_expiry_remainder_template_sid
                        }
                        onChange={(e) => {
                            form.setData(
                                "user_plan_expiry_remainder_template_sid",
                                e.target.value,
                            );

                            form.clearErrors(
                                "user_plan_expiry_remainder_template_sid",
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
                            Update
                        </LoadingSwap>
                    </Button>
                </div>
            </form>
        </section>
    );
}

