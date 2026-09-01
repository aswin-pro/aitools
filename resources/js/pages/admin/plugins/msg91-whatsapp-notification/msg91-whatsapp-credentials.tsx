import { useForm } from "@inertiajs/react";
import { Button } from "@/components/ui/button";
import FormInput from "@/components/admin/form-input";
import { toast } from "sonner";
import { useTranslation } from "react-i18next";
import { LoadingSwap } from "@/components/ui/loading-swap";

interface MSG91WhatsappSettings {
    auth_key: string | null;
    sender_id: string | null;
    admin_number: string | null;
}

interface Props {
    settings: MSG91WhatsappSettings | null;
}

export default function MSG91WhatsappCredentials({ settings }: Props) {
    const { t } = useTranslation();

    const form = useForm({
        auth_key: settings?.auth_key ?? "",
        sender_id: settings?.sender_id ?? "",
        admin_number: settings?.admin_number ?? "",
    });

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        form.post(route("admin.msg91_whatsapp_notification_settings.update"), {
            preserveScroll: true,

            onSuccess: () => {
                toast.success(t("Settings updated successfully."));
            },

            onError: () => {
                toast.error(t("Failed to update settings!"));
            },
        });
    };

    return (
        <form onSubmit={submit} className="space-y-6">
            <div>
                <h2 className="text-lg font-semibold">
                    {t("MSG91 Whatsapp Notification Credentials")}
                </h2>
            </div>

            <div className="grid gap-6 md:grid-cols-3 items-start">
                <FormInput
                    id="auth_key"
                    name="auth_key"
                    label={t("Auth Key")}
                    placeholder={t("Auth Key")}
                    required
                    value={form.data.auth_key}
                    onChange={(e) => {
                        form.setData("auth_key", e.target.value);
                        form.clearErrors("auth_key");
                    }}
                    error={form.errors.auth_key}
                />

                <FormInput
                    id="sender_id"
                    name="sender_id"
                    label={t("Integrated Number")}
                    placeholder={t("Sender ID")}
                    required
                    value={form.data.sender_id}
                    onChange={(e) => {
                        form.setData("sender_id", e.target.value);
                        form.clearErrors("sender_id");
                    }}
                    error={form.errors.sender_id}
                />

                <FormInput
                    id="admin_number"
                    name="admin_number"
                    type="number"
                    label={t("Admin Whatsapp Number (with country code)")}
                    placeholder={t("Whatsapp Number")}
                    required
                    value={form.data.admin_number}
                    onChange={(e) => {
                        form.setData("admin_number", e.target.value);
                        form.clearErrors("admin_number");
                    }}
                    error={form.errors.admin_number}
                />
            </div>

            <div className="text-sm text-muted-foreground">
                {t("If you did not get these credentials, create a")}{" "}
                <a
                    href="https://msg91.com/in"
                    target="_blank"
                    rel="noopener noreferrer"
                    className="text-primary underline"
                >
                    {t("new MSG91 Whatsapp Notification Credentials.")}
                </a>
            </div>

            <div className="flex justify-end">
                <Button type="submit" disabled={form.processing}>
                    <LoadingSwap isLoading={form.processing}>
                        {t("Update")}
                    </LoadingSwap>
                </Button>
            </div>
        </form>
    );
}
