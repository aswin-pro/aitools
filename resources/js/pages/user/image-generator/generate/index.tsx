import Heading from '@/components/heading';
import ImageGeneratorForm from '@/components/user/forms/image-generator-form';
import AppLayout from '@/layouts/app/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

export default function Index({
    response,
}: {
    response: Record<string, string>;
}) {
    // breadcrumbs
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: route('dashboard.user.overview'),
        },
        {
            title: 'Image Generator',
            href: route('dashboard.user.image-generator.index'),
        },
        {
            title: 'Generate',
            href: '#',
        },
    ];

    // translation
    const { t } = useTranslation();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* Meta Data */}
            <Head title="Generate Image" />

            {/* heading */}
            <Heading
                t={t}
                title="Generate Image"
                description="Create images with AI from your prompt."
            />

            {/* Form */}
            <ImageGeneratorForm t={t} response={response} />
        </AppLayout>
    );
}
