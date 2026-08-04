import { Button } from '@/components/ui/button';
import { FieldType, Supplier } from '@/types';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import DynamicRenderFields from '../form/dynamic-render-fields';
import { LoadingSwap } from '../ui/loading-swap';

export default function SupplierForm({ supplier }: { supplier?: Supplier }) {
    // i18n
    const { t } = useTranslation();
    const isEdit = !!supplier;

    const fields: FieldType[] = [
        {
            id: 'company_name',
            fieldType: 'input',
            label: 'Company Name',
            props: {
                placeholder: 'Company Name',
                defaultValue: supplier?.company_name,
                required: false,
            },
        },
        {
            id: 'name',
            label: 'Contact Person Name',
            fieldType: 'input',
            props: {
                placeholder: 'Full name',
                defaultValue: supplier?.name,
                minLength: 4,
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
                defaultValue: supplier?.email,
                autoComplete: 'new-email',
                required: false,
            },
        },
        {
            id: 'mobile_number',
            label: 'Mobile Number',
            fieldType: 'input',
            props: {
                type: 'tel',
                placeholder: 'Mobile Number',
                defaultValue: supplier?.mobile_number,
                inputMode: 'numeric',
                pattern: '[0-9]*',
                onInput: (e: React.FormEvent<HTMLInputElement>) => {
                    e.currentTarget.value = e.currentTarget.value.replace(
                        /\D/g,
                        '',
                    );
                },
                required: true,
            },
        },
        {
            id: 'alternative_mobile_number',
            label: 'Alternative Mobile Number',
            fieldType: 'input',
            props: {
                type: 'tel',
                placeholder: 'Alternative Mobile Number',
                defaultValue: supplier?.alternative_mobile_number,
                inputMode: 'numeric',
                pattern: '[0-9]*',
                onInput: (e: React.FormEvent<HTMLInputElement>) => {
                    e.currentTarget.value = e.currentTarget.value.replace(
                        /\D/g,
                        '',
                    );
                },
                required: false,
            },
        },
        {
            id: 'address',
            label: 'Address',
            fieldType: 'input',
            props: {
                placeholder: 'Address',
                defaultValue: supplier?.address,
                required: false,
            },
        },
        {
            id: 'business_address',
            label: 'Business Address',
            fieldType: 'input',
            props: {
                placeholder: 'Business Address',
                defaultValue: supplier?.business_address,
                required: true,
            },
        },
        {
            id: 'bank_name',
            label: 'Bank Name',
            fieldType: 'input',
            props: {
                placeholder: 'Bank Name',
                defaultValue: supplier?.bank_name,
                required: false,
            },
        },
        {
            id: 'bank_account_holder_name',
            label: 'Bank Account Holder Name',
            fieldType: 'input',
            props: {
                placeholder: 'Bank Account Holder Name',
                defaultValue: supplier?.bank_account_holder_name,
                required: false,
            },
        },
        {
            id: 'bank_account_number',
            label: 'Bank Account Number',
            fieldType: 'input',
            props: {
                placeholder: 'Bank Account Number',
                defaultValue: supplier?.bank_account_number,
                required: false,
            },
        },
        {
            id: 'ifsc_code',
            label: 'IFSC Code',
            fieldType: 'input',
            props: {
                placeholder: 'IFSC Code',
                defaultValue: supplier?.ifsc_code,
                required: false,
            },
        },
        {
            id: 'upi_id',
            label: 'UPI ID',
            fieldType: 'input',
            props: {
                placeholder: 'UPI ID',
                defaultValue: supplier?.upi_id,
                required: false,
            },
        },
    ];

    return (
        <Form
            method={isEdit ? 'put' : 'post'}
            action={
                isEdit
                    ? route('dashboard.suppliers.update', supplier.supplier_id)
                    : route('dashboard.suppliers.store')
            }
            resetOnSuccess={!isEdit}
            options={{ preserveScroll: true }}
            onSuccess={() => {
                toast.success(t('Success!'));
            }}
        >
            {({ processing, errors }) => (
                <div className="space-y-5">
                    <div className="grid grid-cols-1 items-start gap-6 md:grid-cols-4">
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
