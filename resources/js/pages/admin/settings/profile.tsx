import { type BreadcrumbItem, type SharedData } from "@/types";
import { Transition } from "@headlessui/react";
import { Head, Link, useForm, usePage } from "@inertiajs/react";
import { FormEventHandler, useEffect } from "react";
import DeleteUser from "@/components/delete-user";
import HeadingSmall from "@/components/heading-small";
import InputError from "@/components/input-error";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import AppLayout from "@/layouts/app-layout";
import SettingsLayout from "@/layouts/settings/layout";
import { toast } from "sonner";
import { ProfileForm } from "@/types";
import { useRef } from "react";

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

export default function Profile({
    mustVerifyEmail,
    status,
}: {
    mustVerifyEmail: boolean;
    status?: string;
}) {
    const { auth, flash } = usePage<SharedData>().props;

    const fileInputRef = useRef<HTMLInputElement>(null);

    const { data, setData, post, errors, processing, recentlySuccessful } =
        useForm<ProfileForm>({
        name: auth.user.name,
        email: auth.user.email,
        profile_picture: null,
        });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route("admin.update.account"), {
            forceFormData: true,
        });
    };

    useEffect(() => {
    if (flash.success) {
        toast.success(flash.success);
    }

    if (flash.error) {
        toast.error(flash.error);
    }
}, [flash]);
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Profile settings" />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall
                        title="Profile information"
                        description="Update your name and email address"
                    />

                    <form onSubmit={submit} noValidate className="space-y-6">

                <div className="grid md:grid-cols-2 gap-7">

                        <div className="grid gap-2">
                            <Label htmlFor="name">Name</Label>

                            <Input
                                id="name"
                                className="mt-1 block w-full"
                                value={data.name}
                                onChange={(e) =>
                                    setData("name", e.target.value)
                                }
                                required
                                autoComplete="name"
                                placeholder="Full name"
                            />

                            <InputError
                                message={errors.name}
                            />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="email">Email address</Label>

                            <Input
                                id="email"
                                type="email"
                                className="mt-1 block w-full"
                                value={data.email}
                                onChange={(e) =>
                                    setData("email", e.target.value)
                                }
                                required
                                autoComplete="username"
                                placeholder="Email address"
                            />

                            <InputError
                                message={errors.email}
                            />
                        </div>

                                                <div className="grid col-span-2 gap-2">
                            <Label htmlFor="Profile">Profile Picture</Label>

                            <Input
                                ref={fileInputRef}
                                type="file"
                                accept="image/*"
                                onChange={(e) => {
                                    const file = e.target.files?.[0];

                                    console.log(file);

                                    if (!file) return;

                                    if (file.size > 1024 * 1024) {
                                        toast.error(
                                            "Profile photo must be less than 1 MB.",
                                        );
                                        setData("profile_picture", null);
                                        if (fileInputRef.current) {
                                            fileInputRef.current.value = "";
                                        }
                                        return;
                                    }

                                    setData("profile_picture", file);
                                }}
                            />

                            <InputError
                                message={errors.profile_picture}
                            />
                        </div>
</div>


                        {/* 
                        {mustVerifyEmail &&
                            auth.user.email_verified_at === null && (
                                <div>
                                    <p className="mt-2 text-sm text-neutral-800">
                                        Your email address is unverified.
                                        <Link
                                            href={route("verification.send")}
                                            method="post"
                                            as="button"
                                            className="rounded-md text-sm text-neutral-600 underline hover:text-neutral-900 focus:ring-2 focus:ring-offset-2 focus:outline-hidden"
                                        >
                                            Click here to re-send the
                                            verification email.
                                        </Link>
                                    </p>

                                    {status === "verification-link-sent" && (
                                        <div className="mt-2 text-sm font-medium text-green-600">
                                            A new verification link has been
                                            sent to your email address.
                                        </div>
                                    )}
                                </div>
                            )} */}

                        <div className="flex items-center gap-4">
                            <Button disabled={processing}>Save</Button>

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

                <DeleteUser />
            </SettingsLayout>
        </AppLayout>
    );
}
