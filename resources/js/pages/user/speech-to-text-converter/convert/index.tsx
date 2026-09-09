import Heading from '@/components/heading';
import { Card, CardContent } from '@/components/ui/card';
import { GeneratedContentEditor } from '@/components/user/forms/content-editor-form';
import SpeechToTextConversionForm from '@/components/user/forms/speech-to-text-conversion-form';
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
            title: 'Speech to Text',
            href: route('dashboard.user.speech-to-text.index'),
        },
        {
            title: 'Convert',
            href: '#',
        },
    ];

    // translation
    const { t } = useTranslation();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* Meta Data */}
            <Head title="Convert Speech to Text" />

            {/* heading */}
            <Heading
                t={t}
                title="Convert Speech to Text"
                description="Upload audio to convert speech into accurate text with AI in seconds."
            />

            <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
                {/* Form */}
                <SpeechToTextConversionForm t={t} />

                {/* Preview */}
                <div>
                    <Card>
                        <CardContent>
                            {/* Title */}
                            <h3 className="mb-3 text-lg font-medium">
                                {t('Preview')}
                            </h3>

                            {/* Content Editor */}
                            <GeneratedContentEditor
                                t={t}
                                content={response.conversion ?? ''}
                                generationId={response.generation_id ?? null}
                                url="dashboard.user.speech-to-text.update"
                            />
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
