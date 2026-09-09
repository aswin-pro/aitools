import Heading from '@/components/heading';
import PlansCard from '@/components/user/subscriptions/plans-card';
import AppLayout from '@/layouts/app/app-layout';
import { BreadcrumbItem } from '@/types';
import { ContentTemplate, Plan } from '@/types/user';
import { Head, usePage } from '@inertiajs/react';
import { useEffect, useRef } from 'react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';

export default function Index({
    plans,
    templates,
}: {
    plans: Plan[];
    templates: ContentTemplate[];
}) {
    // breadcrumbs
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: route('dashboard.user.overview'),
        },
        {
            title: 'Subscriptions',
            href: route('dashboard.user.subscriptions.index'),
        },
        {
            title: 'Plans',
            href: '#',
        },
    ];

    // translation
    const { t } = useTranslation();

    const { flash } = usePage<{
        flash: {
            success?: string;
            error?: string;
        };
    }>().props;

    const shown = useRef(false);

    useEffect(() => {
        if (shown.current) return;

        if (flash.success || flash.error) {
            shown.current = true;

            if (flash.success) toast.success(flash.success);
            if (flash.error) toast.error(flash.error);
        }
    }, [flash.success, flash.error]);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* Meta Data */}
            <Head title="Plans" />

            {/* heading */}
            <Heading
                t={t}
                title="Plans"
                description="Choose the plan that best fits your needs"
            />

            {/* Plans */}
            <PlansCard t={t} plans={plans} templates={templates} />
        </AppLayout>
    );
}
