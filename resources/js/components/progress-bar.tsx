import { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { Field, FieldLabel } from './ui/field';
import { Progress } from './ui/progress';

export default function ProgressBar() {
    // translation
    const { t } = useTranslation();

    // states
    const { credits } = usePage<SharedData>().props;

    const total = credits.ai_credits.total || 0;
    const used = credits.ai_credits.used || 0;

    // progress
    const progress =
        total === 0 ? 0 : Math.round(Math.min(100, (used / total) * 100));

    return (
        <Field className="w-full -space-y-1 px-0.5">
            <FieldLabel htmlFor="progress-upload">
                <span>{t('AI Credits')}</span>
                <span className="ms-auto">{progress}%</span>
            </FieldLabel>
            <Progress value={progress} id="progress-upload" className="h-1" />
        </Field>
    );
}
