import { Form, Head, usePage } from "@inertiajs/react";
import { toast } from "sonner";
import { useTranslation } from "react-i18next";

import FormInput from "@/components/admin/form-input";
import { Button } from "@/components/ui/button";
import { LoadingSwap } from "@/components/ui/loading-swap";
import AppLayout from "@/layouts/app/app-layout";
import { BreadcrumbItem } from "@/types";

type Settings = {
    tawk_chat_key: string;
};

type tawktochatPageProps = {
    settings: Settings;
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
        title: "Tawk.to Chatbot",
        href: "#",
    },
];

export default function TawkChat() {
    const { settings } =
        usePage<tawktochatPageProps>().props;

    const { t } = useTranslation();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Tawk.to Chatbot")} />

            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <p className="text-sm text-muted-foreground">
                            {t("Overview")}
                        </p>

                        <h1 className="text-2xl font-semibold tracking-tight">
                            {t("Tawk.to Settings")}
                        </h1>
                    </div>
                </div>

                <div className="rounded-xl border bg-card">
                    <div className="border-b px-6 py-5">
                        <h2 className="font-semibold">
                            {t("Tawk.to Credentials")}
                        </h2>

                        <p className="mt-1 text-sm text-muted-foreground">
                            {t(
                                "Configure Tawk.to Chatbot settings for your application.",
                            )}
                        </p>
                    </div>

                    <Form
                        action={route(
                            "admin.tawkchat_settings.update",
                        )}
                        method="post"
                        className="p-6"
                        onSuccess={() => {
                            toast.success(
                                t(
                                    "Tawk.to Chatbot settings updated successfully!",
                                ),
                            );
                        }}
                        onError={() => {
                            toast.error(
                                t(
                                    "Error updating Tawk.to Chatbot settings",
                                ),
                            );
                        }}
                    >
                        {({ errors, processing, clearErrors }) => (
                            <>
                                <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <FormInput
                                        id="tawk_chat_key"
                                        name="tawk_chat_key"
                                        type="text"
                                        label={t("Tawk.to Chatbot URL (s1.src)")}
                                        defaultValue={
                                            settings.tawk_chat_key
                                        }
                                        placeholder={t(
                                            "Google Analytics ID",
                                        )}
                                        required
                                        error={errors.tawk_chat_key}
                                        onChange={() =>
                                            clearErrors("tawk_chat_key")
                                        }
                                    />
                                </div>

                                {/* Analytics help */}
                                <p className="mt-4 text-sm text-muted-foreground">
                                    {t(
                                        "If you did not get a Tawk.to Chatbot URL, create",
                                    )}{" "}
                                    <a
                                        href="https://www.tawk.to/"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="text-primary hover:underline"
                                    >
                                        {t("new Tawk.to Chatbot URL.")}
                                    </a>
                                </p>

                        

                                <div className="mt-6 flex justify-end border-t pt-6">
                                    <Button
                                        type="submit"
                                        disabled={processing}
                                    >
                                        <LoadingSwap
                                            isLoading={processing}
                                        >
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