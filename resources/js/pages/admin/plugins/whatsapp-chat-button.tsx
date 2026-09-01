import { Form, Head, usePage } from "@inertiajs/react";
import { toast } from "sonner";
import { useTranslation } from "react-i18next";

import FormInput from "@/components/admin/form-input";
import { SearchableSelect } from "@/components/admin/searchable-select";
import { Button } from "@/components/ui/button";
import { LoadingSwap } from "@/components/ui/loading-swap";
import AppLayout from "@/layouts/app/app-layout";
import { BreadcrumbItem } from "@/types";
import { useState } from "react";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Plugins",
        href: route("dashboard.admin.plugins.index"),
    },
    {
        title: "WhatsApp Chat Button",
        href: "#",
    },
];

type WhatsAppSettings = {
    show_whatsapp_chatbot: string;
    whatsapp_chatbot_mobile_number: string;
    whatsapp_chatbot_message: string;
};

type WhatsAppChatButtonPageProps = {
    whatsapp_settings: WhatsAppSettings;
};

export default function WhatsAppChatButton() {
    const { whatsapp_settings } =
        usePage<WhatsAppChatButtonPageProps>().props;

    const { t } = useTranslation();

    const [whatsappEnable, setWhatsappEnable] = useState(
        whatsapp_settings.show_whatsapp_chatbot || "0",
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("WhatsApp Chat Button Settings")} />

            <div className="space-y-6">
                <div>
                    <p className="text-sm text-muted-foreground">
                        {t("Overview")}
                    </p>

                    <h1 className="text-2xl font-semibold tracking-tight">
                        {t("WhatsApp Chat Button Settings")}
                    </h1>
                </div>

                <div className="rounded-xl border bg-card">
                    <div className="border-b px-6 py-5">
                        <h2 className="font-semibold">
                            {t("WhatsApp Chat Button Settings")}
                        </h2>

                        <p className="mt-1 text-sm text-muted-foreground">
                            {t(
                                "Configure WhatsApp Chat Button settings for your application.",
                            )}
                        </p>
                    </div>

                    <Form
                        action={route(
                            "admin.whatsapp_chat_button_settings.update",
                        )}
                        method="post"
                        className="p-6"
                        onSuccess={() => {
                            toast.success(
                                t(
                                    "WhatsApp Chat Button settings updated successfully!",
                                ),
                            );
                        }}
                        onError={() => {
                            toast.error(
                                t(
                                    "Error updating WhatsApp Chat Button settings",
                                ),
                            );
                        }}
                    >
                        {({ errors, processing, clearErrors }) => (
                            <>
                                <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                                    <SearchableSelect
                                        label={t(
                                            "Want to display WhatsApp chat button on website?",
                                        )}

                                        value={whatsappEnable}
                                  
                                        onChange={(value) => {
                                            setWhatsappEnable(value);
                                            clearErrors(
                                                "show_whatsapp_chatbot",
                                            );
                                        }}
                                        options={[
                                            {
                                                value: "1",
                                                label: t("Yes"),
                                            },
                                            {
                                                value: "0",
                                                label: t("No"),
                                            },
                                        ]}
                                        placeholder={t(
                                            "Select visibility status",
                                        )}
                                        searchable={false}
                                        name="show_whatsapp_chatbot"
                                       
                                        error={
                                            errors.show_whatsapp_chatbot
                                        }
                                    />

                                    <FormInput
                                        id="whatsapp_chatbot_mobile_number"
                                        name="whatsapp_chatbot_mobile_number"
                                        type="tel"
                                        label={t("WhatsApp Number")}
                                        defaultValue={
                                            whatsapp_settings.whatsapp_chatbot_mobile_number ||
                                            ""
                                        }
                                        placeholder={t("WhatsApp Number")}
                                        error={
                                            errors.whatsapp_chatbot_mobile_number
                                        }
                                        required
                                        maxLength={20}
                                        onChange={(e) => {
                                            e.target.value =
                                                e.target.value.replace(
                                                    /[^0-9]/g,
                                                    "",
                                                );

                                            clearErrors(
                                                "whatsapp_chatbot_mobile_number",
                                            );
                                        }}
                                    />

                                    <FormInput
                                        id="whatsapp_chatbot_message"
                                        name="whatsapp_chatbot_message"
                                        type="text"
                                        label={t("Initial Chat Message")}
                                        defaultValue={
                                            whatsapp_settings.whatsapp_chatbot_message ||
                                            ""
                                        }
                                        placeholder={t(
                                            "Initial Chat Message",
                                        )}
                                        error={
                                            errors.whatsapp_chatbot_message
                                        }
                                        onChange={() =>
                                            clearErrors(
                                                "whatsapp_chatbot_message",
                                            )
                                        }
                                        required
                                    />
                                </div>

                                {/* Help text */}
                                <div className="mt-6">
                                    <p className="text-sm text-muted-foreground">
                                        {t(
                                            "WhatsApp number should include the country code without the + symbol.",
                                        )}
                                    </p>
                                </div>

                                {/* Footer */}
                                <div className="mt-6 flex justify-end border-t pt-6">
                                    <Button
                                        type="submit"
                                        disabled={processing}
                                    >
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