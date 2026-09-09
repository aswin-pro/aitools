import { Button } from '@/components/ui/button';
import i18n from '@/hooks/i18n';
import { router } from '@inertiajs/react';
import { toast } from 'sonner';

export function showDynamicToast(error: string) {
    if (error === 'credits_exceeded') {
        toast.error(i18n.t('Credits limit exceeded.'), {
            action: (
                <Button
                    size="sm"
                    type="button"
                    onClick={() =>
                        router.get(route('dashboard.user.subscriptions.plans'))
                    }
                >
                    {i18n.t('Buy Credits')}
                </Button>
            ),
        });
    } else if (error === 'has_no_feature') {
        toast.error(i18n.t('Please upgrade your plan to unlock this feature.'), {
            action: (
                <Button
                    size="sm"
                    type="button"
                    onClick={() =>
                        router.get(route('dashboard.user.subscriptions.plans'))
                    }
                >
                    {i18n.t('Upgrade')}
                </Button>
            ),
        });
    } else {
        toast.error(i18n.t(error));
    }
}
