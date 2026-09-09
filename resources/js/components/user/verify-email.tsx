import { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { useEffect, useRef } from 'react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import { route } from 'ziggy-js';
import { Button } from '../ui/button';

export default function VerifyEmail() {
    const { t } = useTranslation();
    const { flash } = usePage<SharedData>().props;
    const shownRef = useRef(false);

    // show toast message from route parames
    useEffect(() => {
        if (shownRef.current) return;

        const success = flash?.success?.trim();
        const failed = flash?.error?.trim();

        if (success) toast.success(success);
        else if (failed) toast.error(failed);

        if (success || failed) shownRef.current = true;
    }, [flash]);

    return (
        <div className="flex h-[87dvh] items-center justify-center rounded-2xl border">
            <div className="max-w-xl text-center">
                <div className="text-2xl font-bold mb-1.5">
                    {t('Email Verification Required.')}
                </div>
                <div className="text-sm text-gray-500">
                    {t(
                        `Your email address has not been verified. Please go to your registered email address and verify the email first.`,
                    )}
                </div>
                <div className="mt-6">
                    <Link
                        href={route('dashboard.user.resend.email.verification')}
                    >
                        <Button type="button" color="primary">
                            {t('Verify Email Address')}
                        </Button>
                    </Link>
                </div>
            </div>
        </div>
    );
}
