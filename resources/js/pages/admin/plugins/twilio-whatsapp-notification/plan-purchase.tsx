
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

interface TwilioTemplate {
    id: number;
    template_name: string;
    template_sid: string | null;
    is_enabled: number;
}

interface Props {
    adminTemplate: TwilioTemplate | undefined;
    userTemplate: TwilioTemplate | undefined;
}

export default function PlanPurchase({
    adminTemplate,
    userTemplate,
}: Props) {
    const { t } = useTranslation();

    const form = useForm({
        plan_purchase_admin: adminTemplate?.is_enabled === 1,
        plan_purchase_admin_template_sid:
            adminTemplate?.template_sid ?? "",
        plan_purchase_user: userTemplate?.is_enabled === 1,
        plan_purchase_user_template_sid:
            userTemplate?.template_sid ?? "",
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();

        form.post(
            route(
                "admin.twilio_whatsapp_template_plan_purchase.update",
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
        <section className="space-y-6">
            <div>
                <h2 className="text-lg font-semibold">
                    {t("Plan Purchase Notification")}
                </h2>

                <p className="text-sm text-muted-foreground">
                    {t(
                        "Configure WhatsApp notifications when a plan is purchased.",
                    )}
                </p>
            </div>

            <form onSubmit={submit} className="space-y-6">
                <div className="grid gap-6 md:grid-cols-2 items-start">
                    {/* Admin Notification */}
                    <div className="grid gap-2">
                        <Label htmlFor="plan_purchase_admin">
                            {t("Send Notification to Admin")}
                        </Label>

                        <div className="flex h-10 items-center">
                            <Switch
                                id="plan_purchase_admin"
                                checked={
                                    form.data.plan_purchase_admin
                                }
                                onCheckedChange={(checked) => {
                                    form.setData(
                                        "plan_purchase_admin",
                                        checked,
                                    );

                                    form.clearErrors(
                                        "plan_purchase_admin_template_sid",
                                    );
                                }}
                            />
                        </div>
                    </div>

                    <FormInput
                        id="plan_purchase_admin_template_sid"
                        name="plan_purchase_admin_template_sid"
                        type="text"
                        label={t("Admin Template SID")}
                        placeholder={t("Admin Template SID")}
                        value={
                            form.data
                                .plan_purchase_admin_template_sid
                        }
                        error={
                            form.errors
                                .plan_purchase_admin_template_sid
                        }
                        onChange={(e) => {
                            form.setData(
                                "plan_purchase_admin_template_sid",
                                e.target.value,
                            );

                            form.clearErrors(
                                "plan_purchase_admin_template_sid",
                            );
                        }}
                    />

                    {/* Business Notification */}
                    <div className="grid gap-2">
                        <Label htmlFor="plan_purchase_user">
                            {t("Send Notification to Business")}
                        </Label>

                        <div className="flex h-10 items-center">
                            <Switch
                                id="plan_purchase_user"
                                checked={
                                    form.data.plan_purchase_user
                                }
                                onCheckedChange={(checked) => {
                                    form.setData(
                                        "plan_purchase_user",
                                        checked,
                                    );

                                    form.clearErrors(
                                        "plan_purchase_user_template_sid",
                                    );
                                }}
                            />
                        </div>
                    </div>

                    <FormInput
                        id="plan_purchase_user_template_sid"
                        name="plan_purchase_user_template_sid"
                        type="text"
                        label={t("Business Template SID")}
                        placeholder={t("Business Template SID")}
                        value={
                            form.data
                                .plan_purchase_user_template_sid
                        }
                        error={
                            form.errors
                                .plan_purchase_user_template_sid
                        }
                        onChange={(e) => {
                            form.setData(
                                "plan_purchase_user_template_sid",
                                e.target.value,
                            );

                            form.clearErrors(
                                "plan_purchase_user_template_sid",
                            );
                        }}
                    />
                </div>

                <ShortcodeTable
                    shortcodes={shortcodes.plan_basic}
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

