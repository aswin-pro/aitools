import { Button } from '@/components/ui/button';
import { Company, FieldType, Product, Purchase, Supplier } from '@/types';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import PurchaseItems from '../dashboard/purchases/purchase-items';
import DynamicRenderFields from '../form/dynamic-render-fields';
import { LoadingSwap } from '../ui/loading-swap';

export default function PurchaseForm({
    companies,
    suppliers,
    products,
    purchase,
}: {
    companies: Company[];
    suppliers: Supplier[];
    products: Product[];
    purchase?: Purchase;
}) {
    // i18n
    const { t } = useTranslation();
    const isEdit = !!purchase;

    const paymentModes = [
        { label: 'Cash', value: 'cash' },
        { label: 'Online', value: 'online' },
        { label: 'Cheque', value: 'cheque' },
        { label: 'Due', value: 'due' },
    ];

    const fields: FieldType[] = [
        {
            id: 'company_id',
            fieldType: 'select',
            label: 'Company',
            props: {
                defaultValue: purchase?.company_id?.toString(),
                placeholder: 'Select',
                options: companies.map((company) => ({
                    label: company.company_name,
                    value: company.company_id.toString(),
                })),
                required: true,
            },
        },
        {
            id: 'supplier_id',
            fieldType: 'select',
            label: 'Supplier',
            props: {
                defaultValue: purchase?.supplier_id?.toString(),
                placeholder: 'Select',
                options: suppliers.map((supplier) => ({
                    label: supplier.name,
                    value: supplier.supplier_id.toString(),
                })),
                required: true,
            },
        },
        {
            id: 'payment_mode',
            fieldType: 'select',
            label: 'Payment Mode',
            props: {
                defaultValue: purchase?.payment_mode?.toString() ?? 'cash',
                placeholder: 'Select',
                options: paymentModes.map((mode) => ({
                    label: mode.label,
                    value: mode.value.toString(),
                })),
                required: true,
            },
        },
        {
            id: 'created_at',
            fieldType: 'input',
            label: 'Purchase Date',
            props: {
                defaultValue: purchase?.created_at
                    ? new Date(purchase.created_at).toLocaleDateString('en-GB')
                    : new Date().toLocaleDateString('en-GB'),
                required: true,
                readOnly: true,
            },
        },
    ];

    return (
        <Form
            method={isEdit ? 'put' : 'post'}
            action={
                isEdit
                    ? route(
                          'dashboard.purchases.update',
                          purchase.purchase_history_id,
                      )
                    : route('dashboard.purchases.store')
            }
            resetOnSuccess={!isEdit}
            options={{ preserveScroll: true }}
            onSuccess={() => {
                toast.success(t('Success!'));
            }}
        >
            {({ processing, errors }) => (
                <div className="space-y-5">
                    <h2 className="mb-6 border-b pb-2 text-xl font-medium">
                        {t('Purchase Details')}
                    </h2>
                    {/* Purchase Info */}
                    <div className="grid grid-cols-1 items-start gap-6 md:grid-cols-3 lg:grid-cols-4">
                        <DynamicRenderFields fields={fields} errors={errors} />
                    </div>

                    {/* Purchase Items */}
                    <PurchaseItems products={products} purchase={purchase} />

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
