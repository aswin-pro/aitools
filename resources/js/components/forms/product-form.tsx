import { Button } from '@/components/ui/button';
import { FieldType, MeasurementUnit, Product, ProductCategory } from '@/types';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import DynamicRenderFields from '../form/dynamic-render-fields';
import { LoadingSwap } from '../ui/loading-swap';
import { roundToTwoDecimals } from '@/helpers/format-number';

export default function ProductForm({
    setActionDialogOpen,
    product_categories,
    measurement_units,
    product,
}: {
    setActionDialogOpen: (open: boolean) => void;
    product_categories: ProductCategory[];
    measurement_units: MeasurementUnit[];
    product?: Product;
}) {
    // auth
    const { t } = useTranslation();
    const isEdit = !!product;

    const fields: FieldType[] = [
        {
            show: true,
            id: 'product_category_id',
            fieldType: 'select',
            label: 'Product Category',
            props: {
                defaultValue: product?.product_category_id?.toString(),
                placeholder: 'Select',
                options: product_categories.map((category) => ({
                    label: category.category_name,
                    value: category.product_category_id.toString(),
                })),
                required: true,
            },
        },
        {
            show: true,
            id: 'measurement_unit_id',
            fieldType: 'select',
            label: 'Measurement Unit',
            props: {
                defaultValue: product?.measurement_unit_id?.toString(),
                placeholder: 'Select',
                options: measurement_units.map((unit) => ({
                    label: unit.unit,
                    value: unit.measurement_unit_id.toString(),
                })),
                required: true,
            },
        },
        {
            show: true,
            id: 'product_name',
            fieldType: 'input',
            label: 'Product Name',
            props: {
                defaultValue: product?.product_name,
                placeholder: 'Product Name',
                required: true,
            },
        },
        {
            show: true,
            id: 'tax_percentage',
            fieldType: 'input',
            label: 'Tax Percentage',
            props: {
                type: 'number',
                min: 0,
                max: 100,
                step: '0.01',
                defaultValue: roundToTwoDecimals(product?.tax_percentage ?? 0),
                placeholder: 'Tax Percentage',
                onKeyDown: (e: React.KeyboardEvent<HTMLInputElement>) => {
                    if (e.key === 'e' || e.key === 'E') {
                        e.preventDefault();
                    }
                },
                required: true,
            },
        },
        {
            show: true,
            id: 'purchase_price',
            fieldType: 'input',
            label: 'Purchase Price',
            props: {
                type: 'number',
                min: 0,
                step: '0.01',
                defaultValue: roundToTwoDecimals(product?.purchase_price ?? 0.0),
                placeholder: 'Purchase Price',
                onKeyDown: (e: React.KeyboardEvent<HTMLInputElement>) => {
                    if (e.key === 'e' || e.key === 'E') {
                        e.preventDefault();
                    }
                },
                required: true,
            },
        },
        {
            show: true,
            id: 'selling_price',
            fieldType: 'input',
            label: 'Selling Price',
            props: {
                type: 'number',
                min: 0,
                step: '0.01',
                defaultValue: roundToTwoDecimals(product?.selling_price ?? 0.0),
                placeholder: 'Selling Price',
                onKeyDown: (e: React.KeyboardEvent<HTMLInputElement>) => {
                    if (e.key === 'e' || e.key === 'E') {
                        e.preventDefault();
                    }
                },
                required: true,
            },
        },
        {
            show: isEdit ? false : true,
            id: 'initial_stock',
            fieldType: 'input',
            label: 'Initial Stock',
            props: {
                type: 'number',
                min: 0,
                step: '0.001',
                placeholder: 'Selling Price',
                defaultValue: roundToTwoDecimals(0),
                onKeyDown: (e: React.KeyboardEvent<HTMLInputElement>) => {
                    if (e.key === 'e' || e.key === 'E') {
                        e.preventDefault();
                    }
                },
                required: true,
            },
        },
    ];

    return (
        <Form
            method={isEdit ? 'put' : 'post'}
            action={
                isEdit
                    ? route('dashboard.products.update', product.product_id)
                    : route('dashboard.products.store')
            }
            resetOnSuccess={!isEdit}
            options={{ preserveScroll: true }}
            className="mt-2 space-y-5"
            onSuccess={() => {
                toast.success(t('Success!'));
                setActionDialogOpen(false);
            }}
        >
            {({ processing, errors }) => (
                <>
                    <div className="grid grid-cols-1 items-start gap-6 md:grid-cols-2">
                        <DynamicRenderFields fields={fields} errors={errors} />
                    </div>

                    {/* Submit */}
                    <Button disabled={processing}>
                        <LoadingSwap isLoading={processing}>
                            {t(isEdit ? 'Update' : 'Save')}
                        </LoadingSwap>
                    </Button>
                </>
            )}
        </Form>
    );
}
