import DynamicRenderFields from '@/components/form/dynamic-render-fields';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { LoadingSwap } from '@/components/ui/loading-swap';
import { showDynamicToast } from '@/helpers/show-dynamic-toast';
import { FieldType } from '@/types';
import { Form } from '@inertiajs/react';

export default function TextToSpeechConversionForm({
    t,
}: {
    t: (key: string) => string;
}) {
    // fields
    const fields: FieldType[] = [
        {
            id: 'name',
            label: 'Name',
            fieldType: 'input',
            props: {
                placeholder: t('Ex: Welcome Message'),
                minLength: 3,
                maxLength: 250,
                required: true,
            },
        },
        {
            id: 'voice',
            label: 'Voice',
            fieldType: 'select',
            props: {
                defaultValue: 'alloy',
                placeholder: t('Select'),
                options: [
                    { label: 'Alloy', value: 'alloy' },
                    { label: 'Echo', value: 'echo' },
                    { label: 'Fable', value: 'fable' },
                    { label: 'Onyx', value: 'onyx' },
                    { label: 'Nova', value: 'nova' },
                    { label: 'Shimmer', value: 'shimmer' },
                ],
                required: true,
            },
        },
        {
            id: 'speed',
            label: 'Speed',
            fieldType: 'select',
            props: {
                defaultValue: '1.0',
                placeholder: t('Select'),
                options: [
                    { label: '0.25x', value: '0.25' },
                    { label: '0.5x', value: '0.5' },
                    { label: '1.0x', value: '1.0' },
                    { label: '1.5x', value: '1.5' },
                    { label: '2x', value: '2.0' },
                    { label: '2.5x', value: '2.5' },
                    { label: '3x', value: '3.0' },
                    { label: '3.5x', value: '3.5' },
                    { label: '4x', value: '4.0' },
                ],
                required: true,
            },
        },
        {
            id: 'audio_format',
            label: 'Format',
            fieldType: 'select',
            props: {
                defaultValue: 'mp3',
                placeholder: t('Select'),
                options: [
                    { label: 'mp3', value: 'mp3' },
                    { label: 'opus', value: 'opus' },
                    { label: 'aac', value: 'aac' },
                    { label: 'flac', value: 'flac' },
                ],
                required: true,
            },
        },
        {
            id: 'text',
            label: 'Text',
            fieldType: 'text-area',
            className: 'lg:col-span-2',
            props: {
                placeholder: t(
                    "Ex: Welcome to our website. We're glad you're here and hope you have a great experience.",
                ),
                className: 'min-h-36',
                minLength: 3,
                maxLength: 5000,
                required: true,
            },
        },
    ];

    return (
        <div>
            <Card>
                <CardContent>
                    <Form
                        method="post"
                        action={route(
                            'dashboard.user.text-to-speech.convert.store',
                        )}
                        options={{ preserveScroll: true, preserveState: false }}
                        className="space-y-5"
                        onSuccess={(page) => {
                            const flash = page.props.flash as {
                                error?: string;
                            };

                            if (flash.error) {
                                showDynamicToast(flash.error);
                            }
                        }}
                    >
                        {({ processing, errors }) => (
                            <>
                                <div className="grid grid-cols-1 items-start gap-5 lg:grid-cols-2">
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
                            </>
                        )}
                    </Form>
                </CardContent>
            </Card>
        </div>
    );
}
