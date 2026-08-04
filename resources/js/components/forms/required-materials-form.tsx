import { Button } from '@/components/ui/button';
import { roundToThreeDecimals } from '@/helpers/format-number';
import { FieldType, Product, RequiredMaterial } from '@/types';
import { Form } from '@inertiajs/react';
import { Plus, Trash2 } from 'lucide-react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import DynamicTableField from '../form/dynamic-table-fields';
import { LoadingSwap } from '../ui/loading-swap';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '../ui/table';

export default function RequiredMaterialsForm({
    setActionDialogOpen,
    all_products,
    product,
}: {
    setActionDialogOpen: (open: boolean) => void;
    all_products: Product[];
    product?: Product;
}) {
    // auth
    const { t } = useTranslation();

    // prepare items
    const [items, setItems] = useState<RequiredMaterial[]>(
        product?.required_materials?.length
            ? product.required_materials.map((item) => ({
                  ...item,
                  quantity: roundToThreeDecimals(item.quantity ?? 0),
              }))
            : [
                  {
                      product_id: '',
                      quantity: 1,
                  },
              ],
    );

    const columns: FieldType<RequiredMaterial>[] = [
        {
            id: 'product_id',
            label: 'Product',
            fieldType: 'select',
            props: {
                placeholder: t('Select Product'),
                options: all_products
                    .filter(
                        (allProduct) =>
                            allProduct.product_id !== product?.product_id,
                    )
                    .map((allProduct) => ({
                        label: allProduct.product_name,
                        value: allProduct.product_id.toString(),
                    })),
                required: true,
            },
        },
        {
            id: 'quantity',
            label: 'Qty',
            fieldType: 'input-group',
            props: {
                type: 'number',
                min: 0,
                step: '0.001',
                required: true,
            },
            inputGroup: {
                align: 'inline-end',
                content: (item: RequiredMaterial) => getUnit(item.product_id),
            },
        },
    ];

    // add row
    const addRow = () => {
        setItems((prev) => [
            ...prev,
            {
                product_id: '',
                quantity: 1,
            },
        ]);
    };

    // remove row
    const removeRow = (index: number) => {
        if (items.length === 1) return;

        setItems((prev) => prev.filter((_, i) => i !== index));
    };

    // update item
    const updateItem = (
        index: number,
        field: keyof RequiredMaterial,
        value: string | number,
    ) => {
        setItems((prev) =>
            prev.map((item, i) =>
                i === index ? { ...item, [field]: value } : item,
            ),
        );
    };

    const getUnit = (productId: string) => {
        return all_products.find((product) => product.product_id === productId)
            ?.measurement_unit?.unit;
    };

    return (
        <Form
            method="put"
            action={route(
                'dashboard.products.required-materials',
                product?.product_id,
            )}
            resetOnSuccess
            options={{ preserveScroll: true }}
            className="mt-2 space-y-5"
            onSuccess={() => {
                toast.success(t('Success!'));
                setActionDialogOpen(false);
            }}
        >
            {({ processing, errors }) => (
                <>
                    <div className="mb-3 flex items-center justify-end">
                        <Button
                            type="button"
                            size="icon"
                            onClick={() => addRow()}
                        >
                            <Plus className="h-4 w-4" />
                        </Button>
                    </div>

                    {/* Table */}
                    <div className="overflow-hidden rounded-lg border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead className="w-12 min-w-12 border-r">
                                        {t('S.No')}
                                    </TableHead>
                                    <TableHead className="w-72 min-w-72 border-r">
                                        {t('Product')}
                                    </TableHead>
                                    <TableHead className="w-36 min-w-40 border-r">
                                        {t('Qty')}
                                    </TableHead>
                                    <TableHead className="w-12 min-w-12"></TableHead>
                                </TableRow>
                            </TableHeader>

                            <TableBody>
                                {items.map((item, index) => (
                                    <TableRow key={index}>
                                        <TableCell className="border-r">
                                            {index + 1}
                                        </TableCell>
                                        {columns.map((column) => (
                                            <TableCell
                                                key={column.id}
                                                className="border-r"
                                            >
                                                <DynamicTableField
                                                    column={column}
                                                    item={item}
                                                    index={index}
                                                    updateItem={updateItem}
                                                />
                                            </TableCell>
                                        ))}
                                        <TableCell>
                                            {/* Delete */}
                                            <Button
                                                type="button"
                                                variant="destructive"
                                                size="icon"
                                                onClick={() => removeRow(index)}
                                            >
                                                <Trash2 className="h-4 w-4" />
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    </div>
                    {/* Submit */}
                    <Button disabled={processing}>
                        <LoadingSwap isLoading={processing}>
                            {t('Save')}
                        </LoadingSwap>
                    </Button>
                </>
            )}
        </Form>
    );
}
