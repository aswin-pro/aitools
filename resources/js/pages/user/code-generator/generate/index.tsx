import Heading from '@/components/heading';
import { Card, CardContent } from '@/components/ui/card';
import { ViewContent } from '@/components/user/common/view-content';
import CodeGeneratorForm from '@/components/user/forms/code-generator-form';
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
            title: 'Code Generator',
            href: route('dashboard.user.content-generator.index'),
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
            <Head title={`Generate Code`} />

            {/* heading */}
            <Heading
                t={t}
                title="Generate Code"
                description="Enter your requirements to generate AI-powered code in seconds."
            />

            <div className="grid grid-cols-1 gap-4">
                {/* Form */}
                <CodeGeneratorForm />

                {/* Preview */}
                {response?.code && (
                    <div>
                        <Card>
                            <CardContent>
                                {/* View */}
                                <ViewContent
                                    t={t}
                                    content={response?.code ?? ''}
                                    page="code-generator"
                                />
                            </CardContent>
                        </Card>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
