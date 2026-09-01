import { useForm } from "@inertiajs/react";
import { useTranslation } from "react-i18next";
import { toast } from "sonner";

import { Button } from "@/components/ui/button";
import FormInput from "@/components/admin/form-input";
import { LoadingSwap } from "@/components/ui/loading-swap";

interface MSG91Settings {
    auth_key: string | null;
    sender_id: string | null;
    admin_number: string | null;
}

interface Props {
    settings: MSG91Settings | null;
}

export default function MSG91SmsCredentials({ settings }: Props) {
    const { t } = useTranslation();

    const { data, setData, post, processing, errors, clearErrors } = useForm({
        auth_key: settings?.auth_key ?? "",
        sender_id: settings?.sender_id ?? "",
        admin_number: settings?.admin_number ?? "",
    });

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        post(route("admin.msg91_sms_notification_settings.update"), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success(t("MSG91 SMS settings updated successfully."));
            },
        });
    };

    return (
        <div className="space-y-6">
            <div>
                <h2 className="text-lg font-semibold">
                    {t("MSG91 SMS Notification Credentials")}
                </h2>

                <p className="text-sm text-muted-foreground">
                    {t(
                        "Configure the credentials required to send SMS notifications through MSG91.",
                    )}
                </p>
            </div>

            <form onSubmit={submit} className="space-y-6">
                <FormInput
                    id="auth_key"
                    label={t("Auth Key")}
                    placeholder={t("Enter your MSG91 Auth Key")}
                    value={data.auth_key}
                    onChange={(e) => {
                        setData("auth_key", e.target.value);
                        clearErrors("auth_key");
                    }}
                    error={errors.auth_key}
                    required
                />

                <FormInput
                    id="sender_id"
                    label={t("Sender ID")}
                    placeholder={t("Enter your MSG91 Sender ID")}
                    value={data.sender_id}
                    onChange={(e) => {setData("sender_id", e.target.value)
                        clearErrors("sender_id");
                    }}
                    error={errors.sender_id}
                    required
                />

                <FormInput
                    id="admin_number"
                    label={t("Admin Phone Number")}
                    placeholder={t("Enter admin phone number")}
                    value={data.admin_number}
                    onChange={(e) => {setData("admin_number", e.target.value)
                        clearErrors("admin_number");
                    }}
                    error={errors.admin_number}
                    required
                    type="number"
                />

                <Button type="submit" disabled={processing}>
                    <LoadingSwap isLoading={processing}>
                        {t("Update")}
                    </LoadingSwap>
                </Button>
            </form>
        </div>
    );
}
