import HeadingSmall from '@/components/heading-small';
import PasswordForm from '@/components/user/forms/password-form';
import AppLayout from '@/layouts/app/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

export default function Index() {
    // breadcrumbs
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: route('dashboard.user.overview'),
        },
        {
            title: 'Settings',
            href: route('dashboard.user.settings'),
        },
        {
            title: 'Password',
            href: '#',
        },
    ];

    // translation
    const { t } = useTranslation();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* meta title */}
            <Head title="Password Settings" />

            {/* settings layout */}
            <SettingsLayout>
                <div className="space-y-6">
                    {/* heading */}
                    <HeadingSmall
                        t={t}
                        title="Update password"
                        description="Ensure your account is using a long, random password to stay secure"
                    />

                    {/* Password Form */}
                    <PasswordForm t={t} />
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
