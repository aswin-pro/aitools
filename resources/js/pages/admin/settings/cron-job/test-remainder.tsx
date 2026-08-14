import { router } from "@inertiajs/react";
import { useState } from "react";
import { useTranslation } from "react-i18next";
import { Button } from "@/components/ui/button";
import { toast } from "sonner";
import { LoadingSwap } from "@/components/ui/loading-swap";

export default function TestReminder() {
    const { t } = useTranslation();
    const [processing, setProcessing] = useState(false);

    const testReminder = () => {
        router.get(
            route("dashboard.admin.test.reminder"),
            {},
            {
                preserveScroll: true,
                preserveState: true,

                onStart: () => {
                    setProcessing(true);
                },

                onSuccess: () => {
                    toast.success(
                        t("Reminder emails have been sent successfully."),
                    );
                },

                onError: () => {
                    toast.error(
                        t("Failed to send reminder emails."),
                    );
                },

                onFinish: () => {
                    setProcessing(false);
                },
            },
        );
    };

    return (
        <Button
            type="button"
            variant="outline"
            className="border-black text-black hover:bg-black hover:text-white dark:border-white dark:text-white dark:hover:bg-white dark:hover:text-black"
            disabled={processing}
            onClick={testReminder}
        >
            <LoadingSwap isLoading={processing}>
                {t("Test Reminder")}
            </LoadingSwap>
        </Button>
    );
}