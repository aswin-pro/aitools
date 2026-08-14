import HeadingSmall from "@/components/heading-small";
import { Button } from "@/components/ui/button";
import SettingsLayout from "@/layouts/settings/layout";
import { SharedData, type BreadcrumbItem } from "@/types";
import { Transition } from "@headlessui/react";
import { Form, Head, usePage } from "@inertiajs/react";
import {  useRef } from "react";
import { toast } from "sonner";
import AppLayout from "@/layouts/app/app-layout";
import FormInput from "@/components/admin/form-input";
import { useTranslation } from "react-i18next";
import { LoadingSwap } from "@/components/ui/loading-swap";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Settings",
        href: route("dashboard.admin.edit.account"),
    },
    {
        title: "Password",
        href: "#",
    },
];

export default function Password() {

    const { t } = useTranslation();

    const currentPasswordRef = useRef<HTMLInputElement>(null);
    const newPasswordRef = useRef<HTMLInputElement>(null);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Password Settings")} />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall
                        title={t("Update password")}
                        description={t("Ensure your account is using a long, random password to stay secure.")}
                    />

                    <Form
                        action={route("dashboard.admin.update.password")}
                        method="post"
                        className="space-y-6"
                        resetOnSuccess
                        onSuccess={() => {
                            toast.success(t("Password Updated Successfully!"));
                        }}
                        onError={() => {
                            toast.error(t("Error updating password"));
                        }}
                    >
                        {({
                            errors,
                            processing,
                            recentlySuccessful,
                            clearErrors,
                            reset,
                        }) => (
                            <>
                                <div className="grid gap-6 md:grid-cols-2 items-start">
                                    <FormInput
                                        label={t("Current Password")}
                                        ref={currentPasswordRef}
                                        id="current_password"
                                        name="current_password"
                                        type="password"
                                        autoComplete="current-password"
                                        placeholder={t("Current password")}
                                        error={errors.current_password}
                                        containerClassName="md:col-span-2"
                                        required
                                        onChange={() =>
                                            clearErrors("current_password")
                                        }
                                    />

                                    <FormInput
                                        label={t("New Password")}
                                        ref={newPasswordRef}
                                        id="new_password"
                                        name="new_password"
                                        type="password"
                                        autoComplete="new-password"
                                        placeholder={t("New password")}
                                        error={errors.new_password}
                                        required
                                        onChange={() =>
                                            clearErrors("new_password")
                                        }
                                    />

                                    <FormInput
                                        label={t("Confirm Password")}
                                        id="new_password_confirmation"
                                        name="confirm_password"
                                        type="password"
                                        autoComplete="new-password"
                                        placeholder={t("Confirm password")}
                                        error={errors.confirm_password}
                                        required
                                        onChange={() =>
                                            clearErrors("confirm_password")
                                        }
                                    />
                                </div>

                                <div className="flex items-center gap-4">
                                    <Button disabled={processing}>
                                        <LoadingSwap isLoading={processing}>
                                            {t("Save Password")}
                                        </LoadingSwap>
                                        
                                    </Button>


                                </div>
                            </>
                        )}
                    </Form>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
