import DynamicRenderFields from '@/components/form/dynamic-render-fields';
import { Button } from '@/components/ui/button';
import { LoadingSwap } from '@/components/ui/loading-swap';
import { FieldType } from '@/types';
import { Form } from '@inertiajs/react';
import { toast } from 'sonner';

export default function PasswordForm({ t }: { t: (key: string) => string }) {
    const fields: FieldType[] = [
        {
            id: 'current_password',
            label: 'Current password',
            fieldType: 'input',
            props: {
                type: 'password',
                placeholder: t('Current password'),
                minLength: 6,
                maxLength: 250,
                required: true,
            },
        },
        {
            id: 'password',
            label: 'New password',
            fieldType: 'input',
            props: {
                type: 'password',
                placeholder: t('New password'),
                minLength: 6,
                maxLength: 250,
                required: true,
            },
        },
        {
            id: 'password_confirmation',
            label: 'Confirm password',
            fieldType: 'input',
            props: {
                type: 'password',
                placeholder: t('Confirm password'),
                minLength: 6,
                maxLength: 250,
                required: true,
            },
        },
    ];

    return (
        <Form
            method="post"
            action={route('dashboard.user.settings.password.update')}
            options={{
                preserveScroll: true,
            }}
            resetOnSuccess
            onSuccess={() => {
                toast.success(t('Password updated succesfully!'));
            }}
            onError={() => {
                toast.error(t('Failed!'));
            }}
            className="space-y-5"
        >
            {({ processing, errors }) => (
                <>
                    <div className="grid grid-cols-1 items-start gap-6">
                        <DynamicRenderFields
                            t={t}
                            fields={fields}
                            errors={errors}
                        />
                    </div>

                    {/* Submit */}
                    <Button disabled={processing}>
                        <LoadingSwap isLoading={processing}>
                            {t('Update')}
                        </LoadingSwap>
                    </Button>
                </>
            )}
        </Form>
    );
}
