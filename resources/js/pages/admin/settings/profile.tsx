// import { Form, Head, usePage } from '@inertiajs/react';
/* @chisel-email-verification */
import { Link } from '@inertiajs/react';
/* @end-chisel-email-verification */
// import AccountController from '@/app/Http/Controllers/Admin/AccountController';
import DeleteUser from '@/components/delete-user';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
// import { edit } from '@/routes/profile';
import type { Auth, BreadcrumbItem } from '@/types';
/* @chisel-email-verification */
// import { send } from '@/routes/verification';
/* @end-chisel-email-verification */
import { Head, useForm, usePage } from '@inertiajs/react';
import SettingsLayout from '@/layouts/settings/layout';
import AppLayout from '@/layouts/app-layout';


type PageProps = {
    auth: Auth;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('admin.dashboard'),
    },
    {
        title: 'Settings',
        href: route('admin.index.account'),
    },
    {
        title: 'Profile',
        href: '#',
    },
];

export default function Profile(
    /* @chisel-email-verification */
    {
        mustVerifyEmail,
        status,
    }: {
        mustVerifyEmail: boolean;
        status?: string;
    },
    /* @end-chisel-email-verification */
) {
    const { auth } = usePage<PageProps>().props;

const { data, setData, post, processing, errors } = useForm({
    name: auth.user.name,
    email: auth.user.email,
});

const submit = (e: React.FormEvent) => {
    e.preventDefault();

    post(route('admin.update.account'));
};

    return (
        <>
        <AppLayout breadcrumbs={breadcrumbs}>
        <SettingsLayout>
            <Head title="Profile settings" />

            <h1 className="sr-only">Profile settings</h1>

            <div className="space-y-6">
                <Heading
                    variant="small"
                    title="Profile"
                    description="Update your name and email address"
                /> 

                <form onSubmit={submit} className="space-y-6">

                <div className="grid gap-2">
                    <Label htmlFor="profile photo">Profile Photo</Label>

                    <Input
                        id="profile photo"
                        type="file"
                        // value={data.photo}
                        onChange={(e) => setData('name', e.target.value)}
                    />

                    <InputError message={errors.name} />
                </div>    

                <div className="grid gap-2">
                    <Label htmlFor="name">Name</Label>

                    <Input
                        id="name"
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                    />

                    <InputError message={errors.name} />
                </div>

                <div className="grid gap-2">
                    <Label htmlFor="email">Email</Label>

                    <Input
                        id="email"
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                    />

                    <InputError message={errors.email} />
                </div>

                <Button disabled={processing}>
                    Save
                </Button>

                </form>

            </div>

            <DeleteUser />
            </SettingsLayout>
        </AppLayout>
        </>
    );
}

Profile.layout = {
    breadcrumbs: [
        {
            title: 'Profile settings',
            href: route('admin.edit.account'),
        },
    ],
};
