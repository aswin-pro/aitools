import { Button } from '@/components/ui/button';
import { Company, FieldType } from '@/types';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import DynamicRenderFields from '../form/dynamic-render-fields';
import { LoadingSwap } from '../ui/loading-swap';

export default function CompanyForm({
    setActionDialogOpen,
    company,
}: {
    setActionDialogOpen: (open: boolean) => void;
    company?: Company;
}) {
    // i18n
    const { t } = useTranslation();
    const isEdit = !!company;

    const fields: FieldType[] = [
        {
            id: 'company_name',
            label: 'Company Name',
            fieldType: 'input',
            props: {
                placeholder: 'Company Name',
                defaultValue: company?.company_name,
                required: true,
            },
        },
        {
            id: 'email',
            label: 'Email address',
            fieldType: 'input',
            props: {
                type: 'email',
                placeholder: 'email@example.com',
                defaultValue: company?.email,
                autoComplete: 'new-email',
            },
        },
        {
            id: 'mobile_number',
            label: 'Mobile Number',
            fieldType: 'input',
            props: {
                type: 'tel',
                placeholder: 'Mobile Number',
                defaultValue: company?.mobile_number,
                inputMode: 'numeric',
                pattern: '[0-9]*',
                onInput: (e: React.FormEvent<HTMLInputElement>) => {
                    e.currentTarget.value = e.currentTarget.value.replace(
                        /\D/g,
                        '',
                    );
                },
            },
        },
        {
            id: 'address',
            label: 'Address',
            fieldType: 'input',
            props: {
                placeholder: 'Address',
                defaultValue: company?.address,
                required: false,
            },
        },
        {
            id: 'gstin',
            label: 'GSTIN',
            fieldType: 'input',
            props: {
                placeholder: 'GSTIN',
                defaultValue: company?.gstin,
                required: false,
            },
        },
    ];

    return (
        <Form
            method={isEdit ? 'put' : 'post'}
            action={
                isEdit
                    ? route('dashboard.companies.update', company.company_id)
                    : route('dashboard.companies.store')
            }
            resetOnSuccess={!isEdit}
            options={{ preserveScroll: true }}
            onSuccess={() => {
                toast.success(t('Success!'));
                setActionDialogOpen(false);
            }}
        >
            {({ processing, errors }) => (
                <div className="space-y-5">
                    <div className="grid grid-cols-1 items-start gap-6 md:grid-cols-2">
                        <DynamicRenderFields fields={fields} errors={errors} />
                    </div>

                    {/* Submit */}
                    <Button disabled={processing}>
                        <LoadingSwap isLoading={processing}>
                            {t(isEdit ? 'Update' : 'Save')}
                        </LoadingSwap>
                    </Button>
                </div>
            )}
        </Form>
    );
}
