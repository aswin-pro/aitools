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

export default function NewUserRegistration({ template }: Props) {
    const { t } = useTranslation();

    const form = useForm({
        new_user_registration_admin: template?.is_enabled === 1,
        new_user_registration_admin_template_id:
            template?.template_id ?? "",
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();

        form.post(
            route(
                "admin.msg91_sms_template_user_register.update",
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
        <section>
            <div>
                <h2 className="text-lg font-semibold">
                    {t("New User Registration Notification")}
                </h2>

                <p className="text-sm text-muted-foreground">
                    {t(
                        "Configure SMS notifications for new user registrations.",
                    )}
                </p>
            </div>

            <form
                onSubmit={submit}
                className="space-y-6"
            >
                <div className="mt-10 grid gap-6 md:grid-cols-2 items-start">
                    <div className="grid gap-2">
                        <Label htmlFor="new_user_registration_admin">
                            {t("Send Notification to Admin")}
                        </Label>

                        <div className="flex h-10 items-center">
                            <Switch
                                id="new_user_registration_admin"
                                checked={
                                    form.data
                                        .new_user_registration_admin
                                }
                                onCheckedChange={(checked) => {
                                    form.setData(
                                        "new_user_registration_admin",
                                        checked,
                                    );

                                    form.clearErrors(
                                        "new_user_registration_admin",
                                        "new_user_registration_admin_template_id",
                                    );
                                }}
                            />
                        </div>
                    </div>

                    <FormInput
                        id="new_user_registration_admin_template_id"
                        name="new_user_registration_admin_template_id"
                        type="text"
                        label={t("Admin Template ID")}
                        placeholder={t("Admin Template ID")}
                        value={
                            form.data
                                .new_user_registration_admin_template_id
                        }
                        error={
                            form.errors
                                .new_user_registration_admin_template_id
                        }
                        onChange={(e) => {
                            form.setData(
                                "new_user_registration_admin_template_id",
                                e.target.value,
                            );

                            form.clearErrors(
                                "new_user_registration_admin_template_id",
                            );
                        }}
                    />
                </div>

                <ShortcodeTable
                    shortcodes={shortcodes.user_basic}
                />

                <div className="flex justify-end">
                    <Button
                        type="submit"
                        disabled={form.processing}
                    >
                        <LoadingSwap
                            isLoading={form.processing}
                        >
                            {t("Update")}
                        </LoadingSwap>
                    </Button>
                </div>
            </form>
        </section>
    );
}