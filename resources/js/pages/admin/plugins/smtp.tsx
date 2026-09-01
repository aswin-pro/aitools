import { Form, Head, router, usePage } from "@inertiajs/react";
import { useEffect, useState } from "react";
import { useTranslation } from "react-i18next";
import { toast } from "sonner";

import AppLayout from "@/layouts/app/app-layout";
import FormInput from "@/components/admin/form-input";
import { SearchableSelect } from "@/components/admin/searchable-select";
import { Button } from "@/components/ui/button";
import { LoadingSwap } from "@/components/ui/loading-swap";
import { BreadcrumbItem, SharedData } from "@/types";

type EmailConfiguration = {
    driver: string;
    host: string;
    port: string | number;
    username: string;
    password: string;
    encryption: string;
    address: string;
    name: string;
};

type ConfigItem = {
    config_key: string;
    config_value: string;
};

type SMTPPageProps = {
    settings: {
        site_name: string;
    };
    email_configuration: EmailConfiguration;
    config: ConfigItem[];
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Plugins",
        href: "#",
    },
    {
        title: "SMTP Settings",
        href: "#",
    },
];

export default function SMTP() {
    const { email_configuration, config } = usePage<SMTPPageProps>().props;

    const { t } = useTranslation();

    const emailVerification =
        config.find(
            (item) => item.config_key === "disable_user_email_verification",
        )?.config_value ?? "0";

    const [mailSender, setMailSender] = useState(
        email_configuration.name || "",
    );

    const [mailAddress, setMailAddress] = useState(
        email_configuration.address || "",
    );

    const [mailDriver, setMailDriver] = useState(
        email_configuration.driver || "smtp",
    );

    const [mailHost, setMailHost] = useState(email_configuration.host || "");

    const [mailPort, setMailPort] = useState(
        String(email_configuration.port || ""),
    );

    const [mailUsername, setMailUsername] = useState(
        email_configuration.username || "",
    );

    const [mailPassword, setMailPassword] = useState(
        email_configuration.password || "",
    );

    const [mailEncryption, setMailEncryption] = useState(
        email_configuration.encryption || "tls",
    );

    const [requireEmailVerification, setRequireEmailVerification] = useState(
        emailVerification === "0" ? "yes" : "no",
    );

    const handleTestMail = () => {
        const requiredFields = [
            {
                value: mailSender,
                label: t("Sender Name"),
            },
            {
                value: mailAddress,
                label: t("Sender Email Address"),
            },
            {
                value: mailDriver,
                label: t("Mailer Driver"),
            },
            {
                value: mailHost,
                label: t("Mailer Host"),
            },
            {
                value: mailPort,
                label: t("Mailer Port"),
            },
            {
                value: mailUsername,
                label: t("Mailer Username"),
            },
            {
                value: mailPassword,
                label: t("Mailer Password"),
            },
            {
                value: mailEncryption,
                label: t("Mailer Encryption"),
            },
        ];

        const emptyField = requiredFields.find(
            (field) => !String(field.value ?? "").trim(),
        );

        if (emptyField) {
            toast.error(
                t("Please fill in {{field}} before testing the email.", {
                    field: emptyField.label,
                }),
            );

            return;
        }

        const hasChanges =
            mailSender !== email_configuration.name ||
            mailAddress !== email_configuration.address ||
            mailDriver !== email_configuration.driver ||
            mailHost !== email_configuration.host ||
            mailPort !== String(email_configuration.port) ||
            mailUsername !== email_configuration.username ||
            mailPassword !== email_configuration.password ||
            mailEncryption !== email_configuration.encryption;

        if (hasChanges) {
            toast.info(
                t(
                    "Please click Update first to save the SMTP settings, then test the email.",
                ),
            );

            return;
        }

         router.get(route("admin.plugin.test.email"), {}, {
        preserveScroll: true,
    });
    };

    const { flash } = usePage<SharedData>().props;

    useEffect(() => {
        if (flash?.success) {
            toast.success(flash.success);
        }

        if (flash?.error) {
            toast.error(flash.error);
        }
    }, [flash]);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("SMTP Settings")} />

            <div className="space-y-6">
                <div>
                    <p className="text-sm text-muted-foreground">
                        {t("Overview")}
                    </p>

                    <h1 className="text-2xl font-semibold tracking-tight">
                        {t("SMTP Settings")}
                    </h1>
                </div>

                <div className="rounded-xl border bg-card">
                    <div className="border-b px-6 py-5">
                        <h2 className="font-semibold">
                            {t("SMTP Credentials")}
                        </h2>

                        <p className="mt-1 text-sm text-muted-foreground">
                            {t(
                                "Configure your SMTP settings for sending emails from your application.",
                            )}
                        </p>
                    </div>

                    <Form
                        action={route("admin.smtp_settings.update")}
                        method="post"
                        className="p-6"
                    >
                        {({ errors, processing, clearErrors }) => (
                            <>
                                <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                                    <FormInput
                                        id="mail_sender"
                                        name="mail_sender"
                                        type="text"
                                        label={t("Sender Name")}
                                        value={mailSender}
                                        maxLength={50}
                                        placeholder={t("Sender Name")}
                                        error={errors.mail_sender}
                                        onChange={(e) => {
                                            setMailSender(e.target.value);
                                            clearErrors("mail_sender");
                                        }}
                                        required
                                    />

                                
                                    <FormInput
                                        id="mail_address"
                                        name="mail_address"
                                        type="email"
                                        label={t("Sender Email Address")}
                                        value={mailAddress}
                                        placeholder={t("Sender Email Address")}
                                        error={errors.mail_address}
                                        onChange={(e) => {
                                            setMailAddress(e.target.value);
                                            clearErrors("mail_address");
                                        }}
                                        required
                                    />

                                    <FormInput
                                        id="mail_driver"
                                        name="mail_driver"
                                        type="text"
                                        label={t("Mailer Driver")}
                                        value={mailDriver}
                                        placeholder={t("Mailer Driver")}
                                        error={errors.mail_driver}
                                        onChange={(e) => {
                                            setMailDriver(e.target.value);
                                            clearErrors("mail_driver");
                                        }}
                                        required
                                    />

                                    <FormInput
                                        id="mail_host"
                                        name="mail_host"
                                        type="text"
                                        label={t("Mailer Host")}
                                        value={mailHost}
                                        placeholder={t("Mailer Host")}
                                        error={errors.mail_host}
                                        onChange={(e) => {
                                            setMailHost(e.target.value);
                                            clearErrors("mail_host");
                                        }}
                                        required
                                    />

                                    <FormInput
                                        id="mail_port"
                                        name="mail_port"
                                        type="number"
                                        label={t("Mailer Port")}
                                        value={mailPort}
                                        placeholder={t("Mailer Port")}
                                        error={errors.mail_port}
                                        onChange={(e) => {
                                            setMailPort(e.target.value);
                                            clearErrors("mail_port");
                                        }}
                                        required
                                    />

                                    <SearchableSelect
                                        label={t("Mailer Encryption")}
                                        name="mail_encryption"
                                        value={mailEncryption}
                                        onChange={(value) => {
                                            setMailEncryption(value);

                                            clearErrors("mail_encryption");
                                        }}
                                        options={[
                                            {
                                                value: "tls",
                                                label: t("TLS/STARTTLS"),
                                            },
                                            {
                                                value: "ssl",
                                                label: t("SSL"),
                                            },
                                        ]}
                                        placeholder={t("Select encryption")}
                                        searchable={false}
                                        error={errors.mail_encryption}
                                    />

                                    <FormInput
                                        id="mail_username"
                                        name="mail_username"
                                        type="text"
                                        label={t("Mailer Username")}
                                        value={mailUsername}
                                        placeholder={t("Mailer Username")}
                                        error={errors.mail_username}
                                        onChange={(e) => {
                                            setMailUsername(e.target.value);
                                            clearErrors("mail_username");
                                        }}
                                        required
                                    />

                                    <FormInput
                                        id="mail_password"
                                        name="mail_password"
                                        type="password"
                                        label={t("Mailer Password")}
                                        value={mailPassword}
                                        maxLength={100}
                                        placeholder={t("Mailer Password")}
                                        error={errors.mail_password}
                                        onChange={(e) => {
                                            setMailPassword(e.target.value);
                                            clearErrors("mail_password");
                                        }}
                                        required
                                    />

                                    <SearchableSelect
                                        label={t(
                                            "Require customer email verification?",
                                        )}
                                        name="disable_user_email_verification"
                                        value={requireEmailVerification}
                                        onChange={(value) => {
                                            setRequireEmailVerification(value);

                                            clearErrors(
                                                "disable_user_email_verification",
                                            );
                                        }}
                                        options={[
                                            {
                                                value: "yes",
                                                label: t("Yes"),
                                            },
                                            {
                                                value: "no",
                                                label: t("No"),
                                            },
                                        ]}
                                        placeholder={t("Select an option")}
                                        searchable={false}
                                        error={
                                            errors.disable_user_email_verification
                                        }
                                    />
                                </div>

                                <div className="mt-8 flex justify-end gap-3 border-t pt-6">
                                    <Button
                                        type="button"
                                        variant="outline"
                                        disabled={processing}
                                        onClick={handleTestMail}
                                    >
                                        {t("Test Mail")}
                                    </Button>

                                    <Button type="submit" disabled={processing}>
                                        <LoadingSwap isLoading={processing}>
                                            {t("Update")}
                                        </LoadingSwap>
                                    </Button>
                                </div>
                            </>
                        )}
                    </Form>
                </div>
            </div>
        </AppLayout>
    );
}
