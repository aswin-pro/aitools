import { Button } from '@/components/ui/button';
import { LoadingSwap } from '@/components/ui/loading-swap';
import { RichTextEditor } from '@/components/ui/rich-text-editor';
import { router } from '@inertiajs/react';
import { Fragment, useRef, useState } from 'react';
import { toast } from 'sonner';

export function GeneratedContentEditor({
    t,
    content,
    generationId,
    url,
}: {
    t: (key: string) => string;
    content: string;
    generationId?: string | null;
    url: string;
}) {
    // states
    const [loading, setLoading] = useState(false);
    const [loadingType, setLoadingType] = useState<'generate' | 'update'>(
        'generate',
    );

    // content editor reference
    const contentRef = useRef(content);

    // update content
    const updateContent = () => {
        // return if not generation id
        if (!generationId) return;

        router.put(
            route(url, {
                id: generationId,
            }),
            { content: contentRef.current },
            {
                preserveScroll: true,
                onStart: () => {
                    setLoading(true);
                    setLoadingType('update');
                },
                onSuccess: () => toast.success(t('Success!')),
                onFinish: () => setLoading(false),
            },
        );
    };

    return (
        <Fragment>
            {/* Editor */}
            <RichTextEditor
                t={t}
                loading={loading && loadingType === 'generate'}
                content={content}
                generationId={generationId}
                onChange={(value) => {
                    contentRef.current = value;
                }}
            />

            {/* Save */}
            <Button type="button" className="mt-4" onClick={updateContent}>
                <LoadingSwap isLoading={loading && loadingType === 'update'}>
                    {t('Update')}
                </LoadingSwap>
            </Button>
        </Fragment>
    );
}
