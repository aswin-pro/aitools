import { type BreadcrumbItem, type SharedData } from "@/types";
import { Transition } from "@headlessui/react";
import { Form, Head, usePage } from "@inertiajs/react";
import { useRef } from "react";
import HeadingSmall from "@/components/heading-small";
import { Button } from "@/components/ui/button";
import SettingsLayout from "@/layouts/settings/layout";
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
        title: "Profile",
        href: "#",
    },
];

export default function Profile() {
    const { t } = useTranslation();

    const { auth,  upload } = usePage<SharedData>().props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Profile Settings")} />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall
                        title={t("Profile information")}
                        description={t("Update your name and email address")}
                    />

                    <Form
                        action={route("dashboard.admin.update.account")}
                        method="post"
                        encType="multipart/form-data"
                        options={{ preserveScroll: true }}
                        resetOnSuccess={false}
                        className="space-y-6"
                        onSuccess={() => {
                            toast.success(t("Profile Updated Successfully!"));
                        }}
                        onError={() => {
                            toast.error(t("Error updating profile"));
                        }}
                    >
                        {({
                            errors,
                            processing,
                            recentlySuccessful,
                            clearErrors,
                            setError,
                        }) => {
                            const handleProfilePictureChange = (
                                e: React.ChangeEvent<HTMLInputElement>,
                            ) => {
                                const file = e.target.files?.[0];

                                if (!file) return;

                                if (file.size > upload.size_limit * 1024) {
                                    toast.error(
                                        t(
                                            "Profile photo must be less than {{size}} KB.",
                                            {
                                                size: upload.size_limit,
                                            },
                                        ),
                                    );

                                    setError(
                                        "profile_picture",
                                        t(
                                            "Profile photo must be less than {{size}} KB.",
                                            {
                                                size: upload.size_limit,
                                            },
                                        ),
                                    );

                                    e.target.value = "";
                                }
                            };

                            return (
                                <>
                                    <div className="grid gap-7 md:grid-cols-2 items-start">
                                        <FormInput
                                            id="name"
                                            name="name"
                                            label={t("Name")}
                                            required
                                            defaultValue={auth.user.name}
                                            autoComplete="name"
                                            placeholder={t("Full name")}
                                            error={errors.name}
                                            onChange={() => clearErrors("name")}
                                        />

                                        <FormInput
                                            id="email"
                                            name="email"
                                            type="email"
                                            label={t("Email Address")}
                                            required
                                            defaultValue={auth.user.email}
                                            autoComplete="username"
                                            placeholder={t("Email address")}
                                            error={errors.email}
                                            onChange={() =>
                                                clearErrors("email")
                                            }
                                        />

                                        <FormInput
                                            id="profile_picture"
                                            name="profile_picture"
                                            type="file"
                                            label={t("Profile Picture")}
                                            accept="image/*"
                                            error={errors.profile_picture}
                                            containerClassName="md:col-span-2"
                                            onChange={
                                                handleProfilePictureChange
                                            }
                                        />
                                    </div>

                                    <div className="flex items-center gap-4">
                                        <Button disabled={processing}>
                                            <LoadingSwap isLoading={processing}>
                                                {t("Save")}
                                            </LoadingSwap>
                                        </Button>
                                    </div>
                                </>
                            );
                        }}
                    </Form>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
