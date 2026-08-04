import { Button } from '@/components/ui/button';
import { Company, Customer, FieldType, Product, Sale } from '@/types';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import SalesItems from '../dashboard/sales/sale-items';
import { TransportationDetails } from '../dashboard/sales/transportation-details';
import DynamicRenderFields from '../form/dynamic-render-fields';
import { LoadingSwap } from '../ui/loading-swap';

export default function SaleForm({
    companies,
    customers,
    products,
    sale,
}: {
    companies: Company[];
    customers: Customer[];
    products: Product[];
    sale?: Sale;
}) {
    // i18n
    const { t } = useTranslation();
    const isEdit = !!sale;

    const paymentModes = [
        { label: 'Cash', value: 'cash' },
        { label: 'Online', value: 'online' },
        { label: 'Cheque', value: 'cheque' },
        { label: 'Due', value: 'due' },
    ];

    const baseFields: FieldType[] = [
        {
            id: 'company_id',
            label: 'Company',
            fieldType: 'select',
            props: {
                defaultValue: sale?.company_id?.toString(),
                placeholder: 'Select',
                options: companies.map((company) => ({
                    label: company.company_name,
                    value: company.company_id.toString(),
                })),
                required: true,
            },
        },
        {
            id: 'customer_id',
            label: 'Customer',
            fieldType: 'select',
            props: {
                defaultValue: sale?.customer_id?.toString(),
                placeholder: 'Select',
                options: customers.map((customer) => ({
                    label: customer.name,
                    value: customer.customer_id.toString(),
                })),
                required: true,
            },
        },
        {
            id: 'payment_mode',
            label: 'Payment Mode',
            fieldType: 'select',
            props: {
                defaultValue: sale?.payment_mode?.toString() ?? 'cash',
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
            label: 'Sale Date',
            props: {
                defaultValue: sale?.created_at
                    ? new Date(sale.created_at).toLocaleDateString('en-GB')
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
                    ? route('dashboard.sales.update', sale.sale_history_id)
                    : route('dashboard.sales.store')
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
                        {t('Sales Details')}
                    </h2>
                    {/* Sale Info */}
                    <div className="grid grid-cols-1 items-start gap-6 md:grid-cols-3 lg:grid-cols-4">
                        <DynamicRenderFields
                            fields={baseFields}
                            errors={errors}
                        />
                    </div>

                    {/* Sale Items */}
                    <SalesItems products={products} sale={sale} />

                    {/* Transportation Details */}
                    <h2 className="my-6 border-b pb-2 text-xl font-medium">
                        {t('Transportation Details')}
                    </h2>
                    <TransportationDetails
                        transportation_details={
                            sale?.transportation_details ?? {}
                        }
                        mdGridCols={3}
                        lgGridCols={4}
                    />

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
