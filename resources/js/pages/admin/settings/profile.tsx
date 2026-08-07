import { type BreadcrumbItem, type SharedData } from "@/types";
import { Transition } from "@headlessui/react";
import { Form, Head, usePage } from "@inertiajs/react";
import { useEffect, useRef } from "react";

import DeleteUser from "@/components/delete-user";
import HeadingSmall from "@/components/heading-small";
import InputError from "@/components/input-error";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import AppLayout from "@/layouts/app-layout";
import SettingsLayout from "@/layouts/settings/layout";
import { toast } from "sonner";

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
        title: "Profile",
        href: "#",
    },
];

export default function Profile() {
    const { auth, flash } = usePage<SharedData>().props;

    const fileInputRef = useRef<HTMLInputElement>(null);

    useEffect(() => {
        if (flash.success) toast.success(flash.success);
        if (flash.error) toast.error(flash.error);
    }, [flash]);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Profile Settings" />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall
                        title="Profile information"
                        description="Update your name and email address"
                    />

                    <Form
                        action={route("admin.update.account")}
                        method="post"
                        encType="multipart/form-data"
                        resetOnSuccess={false}
                        className="space-y-6"
                    >
                        {({
                            errors,
                            processing,
                            recentlySuccessful,
                            clearErrors,
                            setError,
                        }) => (
                            <>
                                <div className="grid gap-7 md:grid-cols-2">
                                    <div className="grid gap-2">
                                        <Label
                                            htmlFor="name"
                                            required
                                        >
                                            Name
                                        </Label>

                                        <Input
                                            id="name"
                                            name="name"
                                            defaultValue={auth.user.name}
                                            autoComplete="name"
                                            placeholder="Full name"
                                            onChange={() => clearErrors("name")}
                                        />

                                            <InputError
                                                message={errors.name}
                                            />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label
                                            htmlFor="email"
                                            required
                                        >
                                            Email Address
                                        </Label>

                                        <Input
                                            id="email"
                                            name="email"
                                            type="email"
                                            defaultValue={auth.user.email}
                                            autoComplete="username"
                                            placeholder="Email address"
                                            onChange={() => clearErrors("email")}
                                        />

                                            <InputError
                                                message={errors.email}
                                            />
                                    </div>

                                    <div className="grid gap-2 md:col-span-2">
                                        <Label htmlFor="profile_picture">
                                            Profile Picture
                                        </Label>

                                        <Input
                                            ref={fileInputRef}
                                            id="profile_picture"
                                            name="profile_picture"
                                            type="file"
                                            accept="image/*"
                                            onChange={(e) => {
                                                const file =
                                                    e.target.files?.[0];

                                                if (!file) return;

                                                if (
                                                    file.size >
                                                    1024 * 1024
                                                ) {
                                                    toast.error(
                                                        "Profile photo must be less than 1 MB."
                                                    );

                                                    setError(
                                                        "profile_picture",
                                                        "Profile photo must be less than 1 MB."
                                                    );

                                                    if (
                                                        fileInputRef.current
                                                    ) {
                                                        fileInputRef.current.value =
                                                            "";
                                                    }

                                                    return;
                                                }
                                            }}
                                        />

                                            <InputError
                                                message={
                                                    errors.profile_picture
                                                }
                                            />
                                    </div>
                                </div>

                                <div className="flex items-center gap-4">
                                    <Button disabled={processing}>
                                        Save
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

                <DeleteUser />
            </SettingsLayout>
        </AppLayout>
    );
}