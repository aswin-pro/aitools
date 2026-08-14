import { Form } from "@inertiajs/react";
import { useTranslation } from "react-i18next";
import { toast } from "sonner";

import FormTextarea from "@/components/admin/form-textarea";
import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from "@/components/ui/card";
import { LoadingSwap } from "@/components/ui/loading-swap";
import HeadingSmall from "@/components/heading-small";

interface InvoiceEmailSettingsProps {
    emailHeading: string;
    emailFooter: string;
}

export default function InvoiceEmailSettings({
    emailHeading,
    emailFooter,
}: InvoiceEmailSettingsProps) {
    const { t } = useTranslation();

    return (
        <>
            <HeadingSmall
                title={t("Invoice Email Settings")}
                description={t(
                    "Configure the heading and footer used in invoice emails.",
                )}
            />

            <Card className="p-5">
                <Form
                    action={route("dashboard.admin.update.email.setting")}
                    method="post"
                    resetOnSuccess={false}
                    options={{ preserveScroll: true }}
                    noValidate
                    onSuccess={() => {
                        toast.success(
                            t("Invoice Email Settings Updated Successfully!"),
                        );
                    }}
                    onError={() => {
                        toast.error(
                            t("Please check the fields and try again."),
                        );
                    }}
                >
                    {({ errors, processing, clearErrors }) => (
                        <>
                            <div className="grid grid-cols-1 items-start gap-6 md:grid-cols-2">
                                <FormTextarea
                                    id="email_heading"
                                    name="email_heading"
                                    label={t("Email Heading")}
                                    required
                                    rows={4}
                                    defaultValue={emailHeading}
                                    placeholder={t("Enter email heading")}
                                    error={errors.email_heading}
                                    onChange={() =>
                                        clearErrors("email_heading")
                                    }
                                />

                                <FormTextarea
                                    id="email_footer"
                                    name="email_footer"
                                    label={t("Email Footer")}
                                    required
                                    rows={4}
                                    defaultValue={emailFooter}
                                    placeholder={t("Enter email footer")}
                                    error={errors.email_footer}
                                    onChange={() => clearErrors("email_footer")}
                                />
                            </div>

                            <Button
                                className="mt-6"
                                type="submit"
                                disabled={processing}
                            >
                                <LoadingSwap isLoading={processing}>
                                    {t("Save")}
                                </LoadingSwap>
                            </Button>
                        </>
                    )}
                </Form>
            </Card>
        </>
    );
}
