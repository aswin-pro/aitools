import { useForm } from "@inertiajs/react";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { Switch } from "@/components/ui/switch";
import { shortcodes } from "./shortcodes";
import { LoadingSwap } from "@/components/ui/loading-swap";
import FormInput from "@/components/admin/form-input";
import ShortcodeTable from "./short-code-table";
import { useTranslation } from "react-i18next";




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

export default function PlanRenewal({
    adminTemplate,
    userTemplate,
}: Props) {

    const { t } = useTranslation();
    const form = useForm({
        plan_renewal_admin: adminTemplate?.is_enabled === 1,
        plan_renewal_admin_template_sid: adminTemplate?.template_sid ?? "",
        plan_renewal_user: userTemplate?.is_enabled === 1,
        plan_renewal_user_template_sid: userTemplate?.template_sid ?? "",
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();

        form.post(route("admin.twilio_sms_template_plan_renewal.update"), {
            preserveScroll: true,
        });
    };

    return (
        <section className="space-y-6 ">
            <div>
                <h2 className="text-lg font-semibold">
                    {t("Plan Renewal Notification")}
                </h2>

                <p className="text-sm text-muted-foreground">
                    {t("Configure SMS notifications when a plan is renewed.")}
                </p>
            </div>

            <form onSubmit={submit} className="space-y-6">
                <div className="grid gap-6 md:grid-cols-2">
                    <div className="grid gap-2">
                        <Label htmlFor="plan_renewal_admin">
                            {t("Send Notification to Admin")}
                        </Label>

                        <div className="flex h-10 items-center">
                            <Switch
                                id="plan_renewal_admin"
                                checked={form.data.plan_renewal_admin}
                                onCheckedChange={(checked) => {
                                    form.setData(
                                        "plan_renewal_admin",
                                        checked,
                                    );

                                    form.clearErrors(
                                        "plan_renewal_admin_template_sid",
                                    );
                                }}
                            />
                        </div>
                    </div>

                    <FormInput
                        id="plan_renewal_admin_template_sid"
                        name="plan_renewal_admin_template_sid"
                        type="text"
                        label="Admin Template SID"
                        placeholder="Admin Template SID"
                        value={form.data.plan_renewal_admin_template_sid}
                        error={
                            form.errors.plan_renewal_admin_template_sid
                        }
                        onChange={(e) => {
                            form.setData(
                                "plan_renewal_admin_template_sid",
                                e.target.value,
                            );

                            form.clearErrors(
                                "plan_renewal_admin_template_sid",
                            );
                        }}
                    />

                    <div className="grid gap-2">
                        <Label htmlFor="plan_renewal_user">
                            {t("Send Notification to Business")}
                        </Label>

                        <div className="flex h-10 items-center">
                            <Switch
                                id="plan_renewal_user"
                                checked={form.data.plan_renewal_user}
                                onCheckedChange={(checked) => {
                                    form.setData(
                                        "plan_renewal_user",
                                        checked,
                                    );

                                    form.clearErrors(
                                        "plan_renewal_user_template_sid",
                                    );
                                }}
                            />
                        </div>
                    </div>

                    <FormInput
                        id="plan_renewal_user_template_sid"
                        name="plan_renewal_user_template_sid"
                        type="text"
                        label="Business Template SID"
                        placeholder="Business Template SID"
                        value={form.data.plan_renewal_user_template_sid}
                        error={
                            form.errors.plan_renewal_user_template_sid
                        }
                        onChange={(e) => {
                            form.setData(
                                "plan_renewal_user_template_sid",
                                e.target.value,
                            );

                            form.clearErrors(
                                "plan_renewal_user_template_sid",
                            );
                        }}
                    />
                </div>

                <ShortcodeTable shortcodes={shortcodes.plan_basic} />

                <div className="flex justify-end">
                    <Button type="submit" disabled={form.processing}>
                        <LoadingSwap isLoading={form.processing}>
                            {t("Update")}
                        </LoadingSwap>
                    </Button>
                </div>
            </form>
        </section>
    );
}