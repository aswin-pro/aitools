import HeadingSmall from '@/components/heading-small';
import ProfileForm from '@/components/user/forms/profile-form';
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
            title: 'Profile',
            href: '#',
        },
    ];

    // translation
    const { t } = useTranslation();

    return (
        // App Layout
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* meta title */}
            <Head title="Profile Settings" />

            {/* Settings Layout */}
            <SettingsLayout>
                <div className="space-y-6">
                    {/* Heading */}
                    <HeadingSmall
                        t={t}
                        title="Profile information"
                        description="Update your name and email address"
                    />

                    {/* profile form */}
                    <ProfileForm t={t} />
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
