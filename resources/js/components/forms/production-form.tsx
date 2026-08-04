import { Button } from '@/components/ui/button';
import {
    Company,
    Employee,
    FieldType,
    Product,
    Production,
    Supplier,
} from '@/types';
import { Form } from '@inertiajs/react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import ProductionItems from '../dashboard/productions/production-items';
import DynamicRenderFields from '../form/dynamic-render-fields';
import { LoadingSwap } from '../ui/loading-swap';

export default function ProductionForm({
    companies,
    employees,
    suppliers,
    all_products,
    production_products,
    production,
}: {
    companies: Company[];
    employees: Employee[];
    suppliers: Supplier[];
    all_products: Product[];
    production_products: Product[];
    production?: Production;
}) {
    // i18n
    const { t } = useTranslation();
    const isEdit = !!production;
    const [productionType, setProductionType] = useState<string | null>(
        production?.production_type ?? 'in_house',
    );
    const [dates, setDates] = useState<Record<string, Date | undefined>>({
        production_date: production?.production_date
            ? new Date(production.production_date)
            : new Date(),
        expected_delivery_date: production?.expected_delivery_date
            ? new Date(production.expected_delivery_date)
            : new Date(),
    });

    const [hasNegativeBalance, setHasNegativeBalance] = useState(false);

    const productionTypes = [
        { label: 'In House', value: 'in_house' },
        { label: 'Out Source', value: 'outsource' },
    ];

    const fields: FieldType[] = [
        {
            show: true,
            id: 'production_type',
            label: 'Production Type',
            fieldType: 'select',
            props: {
                defaultValue:
                    production?.production_type?.toString() ?? 'in_house',
                placeholder: 'Select',
                options: productionTypes.map((mode) => ({
                    label: mode.label,
                    value: mode.value.toString(),
                })),
                onValueChange: (value) => setProductionType(value),
                required: true,
            },
        },
        {
            show: true,
            id: 'company_id',
            label: 'Company',
            fieldType: 'select',
            props: {
                defaultValue: production?.company_id?.toString(),
                placeholder: 'Select',
                options: companies.map((company) => ({
                    label: company.company_name,
                    value: company.company_id.toString(),
                })),
                required: true,
            },
        },
        {
            show: productionType === 'in_house',
            id: 'employee_id',
            label: 'Employee',
            fieldType: 'select',
            props: {
                defaultValue: production?.employee_id?.toString(),
                placeholder: 'Select',
                options: employees.map((employee) => ({
                    label: employee.name,
                    value: employee.employee_id.toString(),
                })),
                required: true,
            },
        },
        {
            show: productionType === 'outsource',
            id: 'supplier_id',
            label: 'Supplier',
            fieldType: 'select',
            props: {
                defaultValue: production?.supplier_id?.toString(),
                placeholder: 'Select',
                options: suppliers.map((supplier) => ({
                    label: supplier.name,
                    value: supplier.supplier_id.toString(),
                })),
                required: true,
            },
        },
        {
            show: true,
            id: 'production_date',
            label: 'Production Date',
            fieldType: 'date-picker',
            props: {
                placeholder: 'Select',
                required: true,
            },
        },
        {
            show: true,
            id: 'expected_delivery_date',
            label: 'Expected Delivery Date',
            fieldType: 'date-picker',
            props: {
                placeholder: 'Select',
                required: true,
            },
        },
        {
            show: true,
            id: 'production_cost',
            label: 'Production Cost',
            fieldType: 'input-group',
            props: {
                type: 'number',
                min: 0,
                step: '0.01',
                defaultValue: production?.production_cost ?? 0,
                placeholder: 'Production Cost',
                onKeyDown: (e: React.KeyboardEvent<HTMLInputElement>) => {
                    if (e.key === 'e' || e.key === 'E') {
                        e.preventDefault();
                    }
                },
                required: true,
            },
            inputGroup: {
                align: 'inline-start',
                content: '₹',
            },
        },
        {
            show: true,
            id: 'notes',
            label: 'Notes',
            fieldType: 'input',
            props: {
                defaultValue: production?.notes,
                placeholder: 'Notes',
            },
        },
    ];

    return (
        <Form
            method={isEdit ? 'put' : 'post'}
            action={
                isEdit
                    ? route(
                          'dashboard.productions.update',
                          production.production_id,
                      )
                    : route('dashboard.productions.store')
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
                        {t('Production Details')}
                    </h2>
                    {/* Production Info */}
                    <div className="grid grid-cols-1 items-start gap-6 md:grid-cols-3 lg:grid-cols-4">
                        <DynamicRenderFields
                            fields={fields}
                            errors={errors}
                            dates={dates}
                            setDates={setDates}
                        />
                    </div>

                    {/* Production Items */}
                    <ProductionItems
                        all_products={all_products}
                        production_products={production_products}
                        production={production}
                        setHasNegativeBalance={setHasNegativeBalance}
                    />

                    {/* Submit */}
                    <Button disabled={processing || hasNegativeBalance}>
                        <LoadingSwap isLoading={processing}>
                            {t(isEdit ? 'Update' : 'Save')}
                        </LoadingSwap>
                    </Button>
                </div>
            )}
        </Form>
    );
}
