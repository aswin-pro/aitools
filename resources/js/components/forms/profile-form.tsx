import { Button } from '@/components/ui/button';
import { FieldType, type SharedData } from '@/types';
import { Form, usePage } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import DynamicRenderFields from '../form/dynamic-render-fields';
import { LoadingSwap } from '../ui/loading-swap';

export default function ProfileForm() {
    // auth
    const { auth } = usePage<SharedData>().props;
    const { t } = useTranslation();

    const fields: FieldType[] = [
        {
            id: 'name',
            label: 'Name',
            fieldType: 'input',
            props: {
                defaultValue: auth.user.name,
                autoComplete: 'name',
                placeholder: 'Full name',
                required: true,
            },
        },
        {
            id: 'email',
            label: 'Email address',
            fieldType: 'input',
            props: {
                type: 'email',
                defaultValue: auth.user.email,
                autoComplete: 'username',
                placeholder: 'Email address',
                required: true,
            },
        },
    ];

    return (
        <Form
            method="put"
            action={route('dashboard.settings.profile.update')}
            options={{ preserveScroll: true }}
            className="space-y-5"
            onSuccess={() => {
                toast.success(t('Success!'));
            }}
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
