import DynamicRenderFields from '@/components/form/dynamic-render-fields';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { LoadingSwap } from '@/components/ui/loading-swap';
import { showDynamicToast } from '@/helpers/show-dynamic-toast';
import { FieldType } from '@/types';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

export default function CodeGeneratorForm() {
    // translation
    const { t } = useTranslation();

    // fields
    const fields: FieldType[] = [
        {
            id: 'prompt',
            label: 'What code do you want to generate?',
            fieldType: 'text-area',
            props: {
                placeholder: t(
                    'Ex: Write the full React login page code with Tailwind CSS.',
                ),
                className: 'min-h-52',
                minLength: 10,
                maxLength: 1000,
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
                            'dashboard.user.code-generator.generate.store',
                        )}
                        options={{ preserveScroll: true }}
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
                                <div className="grid grid-cols-1 items-start gap-5">
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
