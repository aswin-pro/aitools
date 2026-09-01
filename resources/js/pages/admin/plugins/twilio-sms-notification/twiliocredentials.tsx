
import { useForm } from "@inertiajs/react";
import { Button } from "@/components/ui/button";
import FormInput from "@/components/admin/form-input";
import { toast } from "sonner";


interface TwilioSettings {
    account_sid: string | null;
    auth_token: string | null;
    from_number: string | null;
    admin_number: string | null;
}

interface Props {
    settings: TwilioSettings | null;
}

export default function TwilioCredentials({ settings }: Props) {
    const form = useForm({
        account_sid: settings?.account_sid ?? "",
        auth_token: settings?.auth_token ?? "",
        from_number: settings?.from_number ?? "",
        admin_number: settings?.admin_number ?? "",
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();

        form.post(route("admin.twilio_sms_notification_settings.update"),
        {
            preserveScroll: true,
            onSuccess: () => {
                toast.success(
                    "Twilio Sms Notification settings updated successfully!",
                );
            },

            onError: () => {
                toast.error(
                    "Error updating Twilio Sms Notification settings",
                );
            },
        }
    );

    };

    return (
        <section className="space-y-6">
            <div>
                <h2 className="text-lg font-semibold">
                    Twilio SMS Notification Credentials
                </h2>

                <p className="text-sm text-muted-foreground">
                    Configure the credentials required to send SMS
                    notifications through Twilio.
                </p>
            </div>

            <form onSubmit={submit} className="space-y-6">
                <div className="grid gap-6 md:grid-cols-2">
                    <FormInput
                        id="account_sid"
                        name="account_sid"
                        type="text"
                        label="Account SID"
                        placeholder="Account SID"
                        required
                        value={form.data.account_sid}
                        error={form.errors.account_sid}
                        onChange={(e) => {
                            form.setData("account_sid", e.target.value);
                            form.clearErrors("account_sid");
                        }}
                    />

                    <FormInput
                        id="auth_token"
                        name="auth_token"
                        type="text"
                        label="Auth Token"
                        placeholder="Auth Token"
                        required
                        value={form.data.auth_token}
                        error={form.errors.auth_token}
                        onChange={(e) => {
                            form.setData("auth_token", e.target.value);
                            form.clearErrors("auth_token");
                        }}
                    />

                    <FormInput
                        id="from_number"
                        name="from_number"
                        type="number"
                        label="Twilio Business SMS Number (with country code)"
                        placeholder="Phone Number"
                        required
                        value={form.data.from_number}
                        error={form.errors.from_number}
                        onChange={(e) => {
                            form.setData("from_number", e.target.value);
                            form.clearErrors("from_number");
                        }}
                    />

                    <FormInput
                        id="admin_number"
                        name="admin_number"
                        type="number"
                        label="Admin Phone Number (with country code)"
                        placeholder="Phone Number"
                        required
                        value={form.data.admin_number}
                        error={form.errors.admin_number}
                        onChange={(e) => {
                            form.setData("admin_number", e.target.value);
                            form.clearErrors("admin_number");
                        }}
                    />
                </div>

                <p className="text-sm text-muted-foreground">
                    If you did not get these credentials, create a{" "}
                    <a
                        href="https://www.twilio.com/console"
                        target="_blank"
                        rel="noopener noreferrer"
                        className="underline underline-offset-4"
                    >
                        new Twilio SMS Notification Credential
                    </a>
                    .
                </p>

                <div className="flex justify-end">
                    <Button type="submit" disabled={form.processing}>
                        {form.processing ? "Updating..." : "Update"}
                    </Button>
                </div>
            </form>
        </section>
    );
}