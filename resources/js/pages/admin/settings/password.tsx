import InputError from "@/components/input-error";
import HeadingSmall from "@/components/heading-small";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import AppLayout from "@/layouts/app-layout";
import SettingsLayout from "@/layouts/settings/layout";
import { SharedData, type BreadcrumbItem } from "@/types";
import { Transition } from "@headlessui/react";
import { Form, Head, usePage } from "@inertiajs/react";
import { useEffect, useRef } from "react";
import { toast } from "sonner";
import { adminSettingsNav } from "@/config/admin/setting-nav";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("admin.dashboard"),
    },
    {
        title: "Settings",
        href: route("admin.index.account"),
    },
    {
        title: "Password",
        href: "#",
    },
];

export default function Password() {
    const { flash } = usePage<SharedData>().props;

    const currentPasswordRef = useRef<HTMLInputElement>(null);
    const newPasswordRef = useRef<HTMLInputElement>(null);

    useEffect(() => {
        if (flash.success) toast.success(flash.success);
        if (flash.error) toast.error(flash.error);
    }, [flash]);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Password Settings" />

            <SettingsLayout items={adminSettingsNav}>
                <div className="space-y-6">
                    <HeadingSmall
                        title="Update password"
                        description="Ensure your account is using a long, random password to stay secure."
                    />

                    <Form
                        action={route("admin.update.password")}
                        method="post"
                        className="space-y-6"
                        resetOnSuccess
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
                                    <div className="space-y-2 md:col-span-2">
                                        <Label
                                            htmlFor="current_password"
                                            required
                                        >
                                            Current Password
                                        </Label>

                                        <Input
                                            ref={currentPasswordRef}
                                            id="current_password"
                                            name="current_password"
                                            type="password"
                                            autoComplete="current-password"
                                            placeholder="Current password"
                                            onChange={() => clearErrors("current_password")}
                                        />

                                            <InputError
                                                message={
                                                    errors.current_password
                                                }
                                            />
                                    </div>

                                    <div className="space-y-2">
                                        <Label
                                            htmlFor="new_password"
                                            required
                                        >
                                            New Password
                                        </Label>

                                        <Input
                                            ref={newPasswordRef}
                                            id="new_password"
                                            name="new_password"
                                            type="password"
                                            autoComplete="new-password"
                                            placeholder="New password"
                                            onChange={() => clearErrors("new_password")}
                                        />

                                            <InputError
                                                message={errors.new_password}
                                            />
                                    </div>

                                    <div className="space-y-2">
                                        <Label
                                            htmlFor="new_password_confirmation"
                                            required
                                        >
                                            Confirm Password
                                        </Label>

                                        <Input
                                            id="new_password_confirmation"
                                            name="confirm_password"
                                            type="password"
                                            autoComplete="new-password"
                                            placeholder="Confirm password"
                                            onChange={() => clearErrors("confirm_password")}
                                        />

                                            <InputError
                                                message={
                                                    errors.confirm_password
                                                }
                                            />
                                    </div>
                                </div>

                                <div className="flex items-center gap-4">
                                    <Button disabled={processing}>
                                        Save Password
                                    </Button>

                                    <Transition
                                        show={recentlySuccessful}
                                        enter="transition ease-in-out"
                                        enterFrom="opacity-0"
                                        leave="transition ease-in-out"
                                        leaveTo="opacity-0"
                                    >
                                        <p className="text-sm text-muted-foreground">
                                            Saved.
                                        </p>
                                    </Transition>
                                </div>
                            </>
                        )}
                    </Form>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}