import DynamicRenderFields from '@/components/form/dynamic-render-fields';
import { Button } from '@/components/ui/button';
import { LoadingSwap } from '@/components/ui/loading-swap';
import { FieldType } from '@/types';
import { Chat } from '@/types/user';
import { Form } from '@inertiajs/react';
import { toast } from 'sonner';

export default function ChatTitleForm({
    t,
    chat,
    url,
}: {
    t: (key: string) => string;
    chat: Chat | null;
    url: string;
}) {
    // fields
    const fields: FieldType[] = [
        {
            id: 'title',
            label: 'Title',
            fieldType: 'input',
            props: {
                defaultValue: chat?.chat_title,
                placeholder: t('Title'),
                minLength: 3,
                maxLength: 250,
                required: true,
            },
        },
        {
            show: url === 'dashboard.user.site-analyzer.update',
            id: 'url',
            label: 'URL',
            fieldType: 'input',
            props: {
                type: 'url',
                defaultValue: chat?.attachment,
                placeholder: t('URL'),
                minLength: 3,
                maxLength: 250,
                required: true,
            },
        },
    ];

    return (
        <Form
            method="put"
            action={route(url, {
                chat: chat?.chat_id,
            })}
            options={{ preserveScroll: true, preserveState: false }}
            className="space-y-5"
            onSuccess={() => {
                toast.success(t('Title updated succesfully!'));
            }}
            onError={() => {
                toast.error(t('Failed!'));
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
                            {t('Update')}
                        </LoadingSwap>
                    </Button>
                </>
            )}
        </Form>
    );
}
