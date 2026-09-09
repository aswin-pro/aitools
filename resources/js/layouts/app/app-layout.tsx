import VerifyEmail from '@/components/user/verify-email';
import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import { SharedData, type BreadcrumbItem } from '@/types';
import { usePage } from '@inertiajs/react';
import { useEffect, type ReactNode } from 'react';

interface AppLayoutProps {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
}

export default ({ children, breadcrumbs, ...props }: AppLayoutProps) => {
    // theme
    const { theme, role, auth } = usePage<SharedData>().props;

    useEffect(() => {
        document.documentElement.setAttribute('data-theme', theme);
    }, [theme]);

    return (
        <AppLayoutTemplate breadcrumbs={breadcrumbs} {...props}>
            <div className="mx-auto w-full max-w-7xl p-5">
                {role === 2 && auth.user.email_verified_at === null ? (
                    <VerifyEmail />
                ) : (
                    children
                )}
            </div>
        </AppLayoutTemplate>
    );
};
