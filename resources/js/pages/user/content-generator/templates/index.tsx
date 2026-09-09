import Heading from '@/components/heading';
import TemplatesCard from '@/components/user/content-generator/templates-card';
import AppLayout from '@/layouts/app/app-layout';
import { type BreadcrumbItem } from '@/types';
import { ContentTemplate } from '@/types/user';
import { Head } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

export default function Index({
    templates,
}: {
    templates: Record<number, ContentTemplate[]>;
}) {
    // breadcrumbs
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: route('dashboard.user.overview'),
        },
        {
            title: 'Content Generator',
            href: route('dashboard.user.content-generator.index'),
        },
        {
            title: 'Templates',
            href: '#',
        },
    ];

    // translation
    const { t } = useTranslation();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* Meta Data */}
            <Head title="Templates" />

            {/* heading */}
            <Heading
                t={t}
                title="Templates"
                description="Choose a template to quickly create AI-powered content."
            />

            {/* Templates */}
            <TemplatesCard t={t} templates={templates} />
        </AppLayout>
    );
}
