import DynamicRenderFields from '@/components/form/dynamic-render-fields';
import { Button } from '@/components/ui/button';
import { LoadingSwap } from '@/components/ui/loading-swap';
import { FieldType, type SharedData } from '@/types';
import { Form, usePage } from '@inertiajs/react';
import { toast } from 'sonner';

export default function ProfileForm({ t }: { t: (key: string) => string }) {
    // auth
    const { auth } = usePage<SharedData>().props;

    const fields: FieldType[] = [
        {
            id: 'name',
            label: 'Name',
            fieldType: 'input',
            props: {
                defaultValue: auth.user.name,
                placeholder: t('Full name'),
                minLength: 3,
                maxLength: 250,
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
                placeholder: t('Email address'),
                minLength: 3,
                maxLength: 250,
                required: true,
            },
        },
        {
            id: 'profile_image',
            label: 'Profile Image',
            fieldType: 'input',
            props: {
                type: 'file',
                accept: '.svg, .png, .jpg, .jpeg, .webp, .gif',
            },
        },
    ];

    return (
        <Form
            method="post"
            action={route('dashboard.user.settings.profile.update')}
            options={{ preserveScroll: true }}
            className="space-y-5"
            onSuccess={() => {
                toast.success(t('Profile updated succesfully!'));
            }}
            onError={() => {
                toast.error(t('Failed!'));
            }}
        >
            {({ processing, errors }) => (
                <>
                    <div className="grid grid-cols-1 items-start gap-5">
                        <DynamicRenderFields t={t} fields={fields} errors={errors} />
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
