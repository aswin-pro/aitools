import InputError from "@/components/input-error";
import AppLayout from "@/layouts/app-layout";
import SettingsLayout from "@/layouts/settings/layout";
import { SharedData, type BreadcrumbItem } from "@/types";
import { Transition } from "@headlessui/react";
import { Head, useForm, usePage } from "@inertiajs/react";
import { FormEventHandler, useEffect, useRef } from "react";

import HeadingSmall from "@/components/heading-small";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { toast } from "sonner";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Password settings",
        href: "/settings/password",
    },
];

export default function Password() {
    const passwordInput = useRef<HTMLInputElement>(null);
    const currentPasswordInput = useRef<HTMLInputElement>(null);

    const { flash } = usePage<SharedData>().props;

    const {
        data,
        setData,
        errors,
        post,
        reset,
        processing,
        recentlySuccessful,
    } = useForm({
        current_password: "",
        new_password: "",
        new_password_confirmation: "",
    });

    useEffect(() => {
        if (flash.success) {
            toast.success(flash.success);
        }

        if (flash.error) {
            toast.error(flash.error);
        }
    }, [flash]);

    const updatePassword: FormEventHandler = (e) => {
        e.preventDefault();

        post(route("admin.update.password"), {
            preserveScroll: true,
            onSuccess: () => reset(),
            onError: (errors) => {
                if (errors.password) {
                    reset("new_password", "new_password_confirmation");
                    passwordInput.current?.focus();
                }

                if (errors.current_password) {
                    reset("current_password");
                    currentPasswordInput.current?.focus();
                }
            },
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Profile settings" />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall
                        title="Update password"
                        description="Ensure your account is using a long, random password to stay secure"
                    />

                    <form onSubmit={updatePassword} className="space-y-6">
                        <div className="grid md:grid-cols-2 gap-7">
                            <div className="grid col-span-2 gap-2">
                                <Label htmlFor="current_password">
                                    Current password
                                </Label>

                                <Input
                                    id="current_password"
                                    ref={currentPasswordInput}
                                    value={data.current_password}
                                    onChange={(e) =>
                                        setData(
                                            "current_password",
                                            e.target.value,
                                        )
                                    }
                                    type="password"
                                    className="mt-1 block w-full"
                                    autoComplete="current-password"
                                    placeholder="Current password"
                                />

                                <InputError message={errors.current_password} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="password">New password</Label>

                                <Input
                                    id="password"
                                    ref={passwordInput}
                                    value={data.new_password}
                                    onChange={(e) =>
                                        setData("new_password", e.target.value)
                                    }
                                    type="password"
                                    className="mt-1 block w-full"
                                    autoComplete="new-password"
                                    placeholder="New password"
                                />

                                <InputError message={errors.new_password} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="password_confirmation">
                                    Confirm password
                                </Label>

                                <Input
                                    id="password_confirmation"
                                    value={data.new_password_confirmation}
                                    onChange={(e) =>
                                        setData(
                                            "new_password_confirmation",
                                            e.target.value,
                                        )
                                    }
                                    type="password"
                                    className="mt-1 block w-full"
                                    autoComplete="new-password"
                                    placeholder="Confirm password"
                                />

                                <InputError
                                    message={errors.new_password_confirmation}
                                />
                            </div>
                        </div>

                        <div className="flex items-center gap-4">
                            <Button disabled={processing}>Save password</Button>

                            <Transition
                                show={recentlySuccessful}
                                enter="transition ease-in-out"
                                enterFrom="opacity-0"
                                leave="transition ease-in-out"
                                leaveTo="opacity-0"
                            >
                                <p className="text-sm text-neutral-600">
                                    Saved
                                </p>
                            </Transition>
                        </div>
                    </form>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
