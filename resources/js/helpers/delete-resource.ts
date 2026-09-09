import { router } from '@inertiajs/react';
import { toast } from 'sonner';

type DeleteOptions = {
    url: string;
    t: (key: string) => string;
    onStart?: () => void;
    onFinish?: () => void;
    onSuccess?: () => void;
};

export function deleteResource({
    url,
    t,
    onStart,
    onFinish,
    onSuccess,
}: DeleteOptions) {
    onStart?.();

    router.delete(url, {
        preserveScroll: true,
        preserveState: false,

        onError: (errors) => {
            Object.values(errors).forEach((msg) => toast.error(t(msg)));
        },

        onSuccess: () => {
            toast.success(t('Success!'));
            onSuccess?.();
        },

        onFinish: () => {
            onFinish?.();
        },
    });
}
