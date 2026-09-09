import DynamicRenderFields from '@/components/form/dynamic-render-fields';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { LoadingSwap } from '@/components/ui/loading-swap';
import { showDynamicToast } from '@/helpers/show-dynamic-toast';
import { FieldRenderType, FieldType } from '@/types';
import { ContentTemplateField } from '@/types/user';
import { Form } from '@inertiajs/react';
import { Fragment } from 'react/jsx-runtime';

export default function ContentGeneratorForm({
    t,
    template,
    templateFields,
    languages,
    maxWordsLength,
}: {
    t: (key: string) => string;
    template: string;
    templateFields: ContentTemplateField[];
    languages: Record<string, string>;
    maxWordsLength: string;
}) {
    // fields
    const fields: FieldType[] = [
        ...templateFields.map((field) => ({
            id: field.ai_input.slice(2, -2),
            label: field.field_name,
            fieldType: (field.field_type === 'textarea'
                ? 'text-area'
                : 'input') as FieldRenderType,
            props: {
                placeholder: t(field.field_description),
                className: field.field_type === 'textarea' ? 'min-h-28' : '',
                minLength: 1,
                maxLength: 1000,
                required: true,
            },
        })),
        {
            id: 'tone',
            label: 'Tone',
            fieldType: 'select',
            props: {
                defaultValue: 'Professional',
                placeholder: t('Select'),
                options: [
                    { label: 'Professional', value: 'Professional' },
                    { label: 'Informal', value: 'Informal' },
                    { label: 'Optimistic', value: 'Optimistic' },
                    { label: 'Pessimistic', value: 'Pessimistic' },
                    { label: 'Joyful', value: 'Joyful' },
                    { label: 'Sad', value: 'Sad' },
                    { label: 'Sincere', value: 'Sincere' },
                    { label: 'Hypocritical', value: 'Hypocritical' },
                    { label: 'Fearful', value: 'Fearful' },
                    { label: 'Hopeful', value: 'Hopeful' },
                    { label: 'Happy', value: 'Happy' },
                    { label: 'Serious', value: 'Serious' },
                    { label: 'Funny', value: 'Funny' },
                    { label: 'Bold', value: 'Bold' },
                    { label: 'Casual', value: 'Casual' },
                    { label: 'Excited', value: 'Excited' },
                ],
                required: true,
            },
        },
        {
            id: 'lang',
            label: 'Language',
            fieldType: 'select',
            props: {
                defaultValue: 'en',
                placeholder: t('Select'),
                options: Object.entries(languages).map(([value, label]) => ({
                    label,
                    value,
                })),
                required: true,
            },
        },
        {
            id: 'level',
            label: 'Creativity Level',
            fieldType: 'select',
            props: {
                defaultValue: '0.5',
                placeholder: t('Select'),
                options: [
                    { label: 'Repetitive', value: '0.0' },
                    { label: 'Deterministic', value: '0.2' },
                    { label: 'Original', value: '0.5' },
                    { label: 'Creative', value: '0.8' },
                    { label: 'Imaginative', value: '1.0' },
                ],
                required: true,
            },
        },
        {
            id: 'max_length',
            label: 'Max Word Length',
            fieldType: 'input',
            props: {
                type: 'number',
                defaultValue: Number(maxWordsLength),
                placeholder: t('Ex: 500'),
                min: 50,
                max: Number(maxWordsLength),
                required: true,
            },
        },
        {
            id: 'results',
            label: 'No. Of Results',
            fieldType: 'select',
            props: {
                defaultValue: '1',
                placeholder: t('Select'),
                options: [
                    { label: '1', value: '1' },
                    { label: '2', value: '2' },
                ],
                required: true,
            },
        },
    ];

    return (
        <div>
            <Card>
                <CardContent>
                    {/* Form */}
                    <Form
                        method="post"
                        action={route(
                            'dashboard.user.content-generator.generate.store',
                            {
                                template: template,
                            },
                        )}
                        options={{ preserveScroll: true }}
                        className="space-y-5"
                        onSuccess={(page) => {
                            // get flash
                            const flash = page.props.flash as {
                                error?: string;
                            };

                            // error toast
                            if (flash.error) {
                                showDynamicToast(flash.error);
                            }
                        }}
                    >
                        {({ processing, errors }) => (
                            <Fragment>
                                <div className="grid grid-cols-1 items-start gap-5">
                                    {/* Render Fields */}
                                    <DynamicRenderFields
                                        t={t}
                                        fields={fields}
                                        errors={errors}
                                    />
                                </div>

                                {/* Submit */}
                                <Button disabled={processing}>
                                    <LoadingSwap isLoading={processing}>
                                        {t('Generate')}
                                    </LoadingSwap>
                                </Button>
                            </Fragment>
                        )}
                    </Form>
                </CardContent>
            </Card>
        </div>
    );
}
