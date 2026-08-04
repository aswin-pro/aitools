import InputError from '@/components/form/input-error';
import { Label } from '@/components/ui/label';
import { ReactNode } from 'react';
import { useTranslation } from 'react-i18next';

interface FormFieldProps {
    id: string;
    label: string;
    error?: string;
    children: ReactNode;
    className?: string;
    required?: boolean;
}

export default function FormField({
    id,
    label,
    error,
    children,
    className = '',
    required = false,
}: FormFieldProps) {
    // i18n
    const { t } = useTranslation();

    return (
        <div className={`grid gap-2 ${className}`}>
            {/* Label */}
            <Label htmlFor={id}>
                {t(label)}
                {required && <span className="-mx-1 text-destructive">*</span>}
            </Label>

            {/* Input */}
            {children}

            {/* Error */}
            <InputError message={t(error as string)} />
        </div>
    );
}
