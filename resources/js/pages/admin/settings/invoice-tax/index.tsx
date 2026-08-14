import { type BreadcrumbItem } from "@/types";
import { Form, Head, usePage } from "@inertiajs/react";
import { toast } from "sonner";
import { useTranslation } from "react-i18next";

import AppLayout from "@/layouts/app/app-layout";
import SettingsLayout from "@/layouts/settings/layout";

import HeadingSmall from "@/components/heading-small";
import FormInput from "@/components/admin/form-input";
import FormTextarea from "@/components/admin/form-textarea";

import { Button } from "@/components/ui/button";
import { LoadingSwap } from "@/components/ui/loading-swap";

import { systemSetting } from "@/types/admin";
import { Card } from "@/components/ui/card";
import InvoiceEmailSettings from "./invoice-email-settings";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Settings",
        href: route("dashboard.admin.index.account"),
    },
    {
        title: "Tax Settings",
        href: "#",
    },
];

export default function Index() {
    const { t } = useTranslation();

    const { config } = usePage<systemSetting>().props;

    const configValues = Object.fromEntries(
        config.map((item) => [item.config_key, item.config_value]),
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Tax Settings")} />

            <SettingsLayout>
                <div className="max-w-[5xl] space-y-6">
                    <HeadingSmall
                        title={t("Tax Settings")}
                        description={t(
                            "These details will be used for the invoice.",
                        )}
                    />

                    <Form
                        action={route("dashboard.admin.update.tax.setting")}
                        method="post"
                        resetOnSuccess={false}
                        options={{ preserveScroll: true }}
                        className="space-y-6"
                        onSuccess={() => {
                            toast.success(
                                t("Invoice Setting Updated Successfully!"),
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
                                <Card className="p-5">
                                    <div className=" grid grid-cols-1 items-start gap-6 md:grid-cols-2 lg:grid-cols-3">
                                        <FormInput
                                            id="invoice_prefix"
                                            name="invoice_prefix"
                                            type="text"
                                            label={t("Invoice Number Prefix")}
                                            required
                                            defaultValue={
                                                configValues.invoice_prefix ||
                                                ""
                                            }
                                            placeholder={t(
                                                "Invoice Number Prefix",
                                            )}
                                            error={errors.invoice_prefix}
                                            onChange={() =>
                                                clearErrors("invoice_prefix")
                                            }
                                        />

                                        {/* Name */}
                                        <FormInput
                                            id="invoice_name"
                                            name="invoice_name"
                                            type="text"
                                            label={t("Name")}
                                            required
                                            defaultValue={
                                                configValues.invoice_name || ""
                                            }
                                            placeholder={t("Name")}
                                            error={errors.invoice_name}
                                            onChange={() =>
                                                clearErrors("invoice_name")
                                            }
                                        />

                                        {/* Email */}
                                        <FormInput
                                            id="invoice_email"
                                            name="invoice_email"
                                            type="email"
                                            label={t("Email")}
                                            required
                                            defaultValue={
                                                configValues.invoice_email || ""
                                            }
                                            placeholder={t("Email")}
                                            error={errors.invoice_email}
                                            onChange={() =>
                                                clearErrors("invoice_email")
                                            }
                                        />

                                        {/* Phone */}
                                        <FormInput
                                            id="invoice_phone"
                                            name="invoice_phone"
                                            type="text"
                                            label={t("Phone")}
                                            required
                                            defaultValue={
                                                configValues.invoice_phone || ""
                                            }
                                            placeholder={t("Phone")}
                                            error={errors.invoice_phone}
                                            onChange={() =>
                                                clearErrors("invoice_phone")
                                            }
                                        />

                                        {/* Address */}
                                        <FormTextarea
                                            id="invoice_address"
                                            name="invoice_address"
                                            label={t("Address")}
                                            required
                                            rows={3}
                                            defaultValue={
                                                configValues.invoice_address ||
                                                ""
                                            }
                                            placeholder={t("Address")}
                                            error={errors.invoice_address}
                                            onChange={() =>
                                                clearErrors("invoice_address")
                                            }
                                        />

                                        {/* City */}
                                        <FormInput
                                            id="invoice_city"
                                            name="invoice_city"
                                            type="text"
                                            label={t("City")}
                                            required
                                            defaultValue={
                                                configValues.invoice_city || ""
                                            }
                                            placeholder={t("City")}
                                            error={errors.invoice_city}
                                            onChange={() =>
                                                clearErrors("invoice_city")
                                            }
                                        />

                                        {/* State */}
                                        <FormInput
                                            id="invoice_state"
                                            name="invoice_state"
                                            type="text"
                                            label={t("State/Province")}
                                            required
                                            defaultValue={
                                                configValues.invoice_state || ""
                                            }
                                            placeholder={t("State/Province")}
                                            error={errors.invoice_state}
                                            onChange={() =>
                                                clearErrors("invoice_state")
                                            }
                                        />

                                        {/* ZIP */}
                                        <FormInput
                                            id="invoice_zipcode"
                                            name="invoice_zipcode"
                                            type="text"
                                            label={t("ZIP Code")}
                                            required
                                            defaultValue={
                                                configValues.invoice_zipcode ||
                                                ""
                                            }
                                            placeholder={t("ZIP Code")}
                                            error={errors.invoice_zipcode}
                                            onChange={() =>
                                                clearErrors("invoice_zipcode")
                                            }
                                        />

                                        {/* Country */}
                                        <FormInput
                                            id="invoice_country"
                                            name="invoice_country"
                                            type="text"
                                            label={t("Country")}
                                            required
                                            defaultValue={
                                                configValues.invoice_country ||
                                                ""
                                            }
                                            placeholder={t("Country")}
                                            error={errors.invoice_country}
                                            onChange={() =>
                                                clearErrors("invoice_country")
                                            }
                                        />

                                        {/* Tax Name */}
                                        <FormInput
                                            id="tax_name"
                                            name="tax_name"
                                            type="text"
                                            label={t("Tax Name")}
                                            defaultValue={
                                                configValues.tax_name || ""
                                            }
                                            placeholder={t("Tax Name")}
                                            error={errors.tax_name}
                                            onChange={() =>
                                                clearErrors("tax_name")
                                            }
                                        />

                                        {/* Tax ID */}
                                        <FormInput
                                            id="tax_number"
                                            name="tax_number"
                                            type="text"
                                            label={t("Tax ID")}
                                            defaultValue={
                                                configValues.tax_number || ""
                                            }
                                            placeholder={t("Tax ID")}
                                            error={errors.tax_number}
                                            onChange={() =>
                                                clearErrors("tax_number")
                                            }
                                        />

                                        {/* Tax Value */}
                                        <FormInput
                                            id="tax_value"
                                            name="tax_value"
                                            type="number"
                                            label={t("Tax Value")}
                                            defaultValue={
                                                configValues.tax_value || "0"
                                            }
                                            placeholder={t("Tax Value")}
                                            error={errors.tax_value}
                                            onChange={() =>
                                                clearErrors("tax_value")
                                            }
                                        />

                                        {/* Invoice Footer */}
                                        <FormTextarea
                                            id="invoice_footer"
                                            name="invoice_footer"
                                            label={t("Invoice Footer")}
                                            required
                                            rows={3}
                                            defaultValue={
                                                configValues.invoice_footer ||
                                                ""
                                            }
                                            placeholder={t("Invoice Footer")}
                                            error={errors.invoice_footer}
                                            onChange={() =>
                                                clearErrors("invoice_footer")
                                            }
                                        />
                                    </div>

                                    <div className="mt-6">
                                        <Button
                                            type="submit"
                                            disabled={processing}
                                        >
                                            <LoadingSwap isLoading={processing}>
                                                {t("Save")}
                                            </LoadingSwap>
                                        </Button>
                                    </div>
                                </Card>

                                <InvoiceEmailSettings
    emailHeading={configValues.email_heading || ""}
    emailFooter={configValues.email_footer || ""}
/>
                            </>
                        )}
                    </Form>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
