import Heading from '@/components/heading';
import { Card, CardContent } from '@/components/ui/card';
import { GeneratedContentEditor } from '@/components/user/forms/content-editor-form';
import ContentGeneratorForm from '@/components/user/forms/content-generator-form';
import AppLayout from '@/layouts/app/app-layout';
import { type BreadcrumbItem } from '@/types';
import { ContentTemplateField } from '@/types/user';
import { Head } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

export default function Index({
    template,
    templateFields,
    languages,
    maxWordsLength,
    response,
}: {
    template: string;
    templateFields: ContentTemplateField[];
    languages: Record<string, string>;
    maxWordsLength: string;
    response: Record<string, string>;
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
            title: 'Generate',
            href: '#',
        },
    ];

    // translation
    const { t } = useTranslation();

    // template name
    const templateName = template
        .replace(/_/g, ' ')
        .replace(/\b\w/g, (char) => char.toUpperCase());

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* Meta Data */}
            <Head title={`Generate ${templateName}`} />

            {/* heading */}
            <Heading
                t={t}
                title={`${templateName}`}
                description="Provide the details below to generate high-quality AI-powered content."
            />

            <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
                {/* Form */}
                <ContentGeneratorForm
                    t={t}
                    template={template}
                    templateFields={templateFields}
                    languages={languages}
                    maxWordsLength={maxWordsLength}
                />

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
                                content={response.content ?? ''}
                                generationId={response.generation_id ?? null}
                                url="dashboard.user.content-generator.update"
                            />
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
