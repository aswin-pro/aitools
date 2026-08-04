import { Button } from '@/components/ui/button';
import { FieldType } from '@/types';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import DynamicRenderFields from '../form/dynamic-render-fields';
import { LoadingSwap } from '../ui/loading-swap';

export default function PasswordForm() {
    const { t } = useTranslation();

    const fields: FieldType[] = [
        {
            id: 'current_password',
            label: 'Current password',
            fieldType: 'input',
            props: {
                type: 'password',
                autoComplete: 'current-password',
                placeholder: 'Current password',
                required: true,
            },
        },
        {
            id: 'password',
            label: 'New password',
            fieldType: 'input',
            props: {
                type: 'password',
                autoComplete: 'new-password',
                placeholder: 'New password',
                required: true,
            },
        },
        {
            id: 'password_confirmation',
            label: 'Confirm password',
            fieldType: 'input',
            props: {
                type: 'password',
                autoComplete: 'new-password',
                placeholder: 'Confirm password',
                required: true,
            },
        },
    ];

    return (
        <Form
            method="put"
            action={route('dashboard.settings.password.update')}
            options={{
                preserveScroll: true,
            }}
            resetOnSuccess
            onSuccess={() => {
                toast.success(t('Success!'));
            }}
            className="space-y-5"
        >
            {({ processing, errors }) => (
                <>
                    <div className="grid grid-cols-1 items-start gap-6">
                        <DynamicRenderFields fields={fields} errors={errors} />
                    </div>

                    {/* Submit */}
                    <Button disabled={processing}>
                        <LoadingSwap isLoading={processing}>
                            {t('Save')}
                        </LoadingSwap>
                    </Button>
                </>
            )}
        </Form>
    );
}
