import DynamicTableField from '@/components/form/dynamic-table-fields';
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { roundToThreeDecimals } from '@/helpers/format-number';
import {
    FieldType,
    Product,
    Production,
    ProductionItem,
    RequiredMaterialProduct,
} from '@/types';
import { Plus, Trash2 } from 'lucide-react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';

export default function ProductionItems({
    all_products,
    production_products,
    production,
    setHasNegativeBalance,
}: {
    all_products: Product[];
    production_products: Product[];
    production?: Production;
    setHasNegativeBalance: React.Dispatch<React.SetStateAction<boolean>>;
}) {
    // auth
    const { t } = useTranslation();

    // prepare items
    const [items, setItems] = useState<ProductionItem[]>(
        production?.production_outputs?.length
            ? production.production_outputs.map((item) => ({
                  ...item,
                  quantity: roundToThreeDecimals(item.quantity),
              }))
            : [
                  {
                      product_id: '',
                      quantity: 1,
                  },
              ],
    );

    const columns: FieldType<ProductionItem>[] = [
        {
            id: 'product_id',
            fieldType: 'select',
            props: {
                placeholder: t('Select Product'),
                options: production_products.map((productionProduct) => ({
                    label: productionProduct.product_name,
                    value: productionProduct.product_id.toString(),
                })),
                required: true,
            },
        },
        {
            id: 'quantity',
            fieldType: 'input-group',
            props: {
                type: 'number',
                min: 0,
                step: '0.001',
                required: true,
            },
            inputGroup: {
                align: 'inline-end',
                content: (item: ProductionItem) => getUnit(item.product_id),
            },
        },
    ];

    // get unit
    const getUnit = (productId: string) => {
        return all_products.find((product) => product.product_id === productId)
            ?.measurement_unit?.unit;
    };

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

        setItems((prev) => {
            const updatedItems = prev.filter((_, i) => i !== index);

            setRequiredMaterials(calculateRequiredMaterials(updatedItems));

            return updatedItems;
        });
    };

    const calculateRequiredMaterials = (productionItems: ProductionItem[]) => {
        const materialMap = new Map<string, RequiredMaterialProduct>();

        productionItems.forEach((item) => {
            const productionProduct = production_products.find(
                (p) => p.product_id === item.product_id,
            );

            if (!productionProduct?.required_materials) return;

            productionProduct.required_materials.forEach((material) => {
                const product = all_products.find(
                    (p) => p.product_id === material.product_id,
                );

                if (!product) return;

                const requiredQty = roundToThreeDecimals(
                    roundToThreeDecimals(material.quantity) *
                        roundToThreeDecimals(item.quantity),
                );

                const existing = materialMap.get(material.product_id);

                if (existing) {
                    existing.required_qty = roundToThreeDecimals(
                        existing.required_qty + requiredQty,
                    );
                    existing.balance_qty = roundToThreeDecimals(
                        existing.stock - existing.required_qty,
                    );
                } else {
                    const inventory = roundToThreeDecimals(
                        product.inventory_sum_stock,
                    );

                    materialMap.set(material.product_id, {
                        product_id: product.product_id,
                        product_name: product.product_name,
                        required_qty: requiredQty,
                        stock: inventory,
                        balance_qty: roundToThreeDecimals(
                            inventory - requiredQty,
                        ),
                        measuring_unit: product.measurement_unit.unit,
                    });
                }
            });
        });

        const materials = [...materialMap.values()];

        setHasNegativeBalance(materials.some((item) => item.balance_qty < 0));

        return [...materialMap.values()];
    };

    // required materials
    const [requiredMaterials, setRequiredMaterials] = useState<
        RequiredMaterialProduct[]
    >(() => calculateRequiredMaterials(items));

    // update item
    const updateItem = (
        index: number,
        field: keyof ProductionItem,
        value: string | number,
    ) => {
        // set items
        setItems((prev) => {
            // update particular value only
            const updatedItems = prev.map((item, i) =>
                i === index ? { ...item, [field]: value } : item,
            );

            // calculate required materials
            setRequiredMaterials(calculateRequiredMaterials(updatedItems));

            // return
            return updatedItems;
        });
    };

    return (
        <div className="mt-6 grid grid-cols-1 gap-6">
            <div className="border-r pe-4">
                <h2 className="mb-6 border-b pb-2 text-xl font-medium">
                    {t('Production Items')}
                </h2>

                <div className="mb-3 flex items-center justify-end">
                    <Button type="button" size="icon" onClick={() => addRow()}>
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
                                <TableHead className="w-36 min-w-36 border-r">
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
                                    <TableCell className="text-center">
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
            </div>

            <div>
                <h2 className="mb-6 border-b pb-2 text-xl font-medium">
                    {t('Required Materials')}
                </h2>

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
                                <TableHead className="w-36 min-w-36 border-r">
                                    {t('Stock')}
                                </TableHead>
                                <TableHead className="w-36 min-w-36 border-r">
                                    {t('Required Qty')}
                                </TableHead>
                                <TableHead className="w-36 min-w-36 border-r">
                                    {t('Balance Qty')}
                                </TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            {requiredMaterials.length > 0 ? (
                                requiredMaterials.map((item, index) => (
                                    <TableRow key={index}>
                                        <TableCell className="border-r">
                                            {index + 1}
                                            <input
                                                type="hidden"
                                                name={`required_materials[${index}][product_id]`}
                                                value={item.product_id}
                                            />
                                        </TableCell>
                                        <TableCell className="border-r">
                                            {item.product_name}

                                            <input
                                                type="hidden"
                                                name={`required_materials[${index}][product_name]`}
                                                value={item.product_name}
                                            />
                                        </TableCell>

                                        <TableCell className="border-r font-medium text-green-600">
                                            {roundToThreeDecimals(item.stock)}{' '}
                                            {item.measuring_unit}
                                            <input
                                                type="hidden"
                                                name={`required_materials[${index}][stock]`}
                                                value={item.stock}
                                            />
                                        </TableCell>

                                        <TableCell className="border-r font-medium text-red-600">
                                            {roundToThreeDecimals(
                                                item.required_qty,
                                            )}{' '}
                                            {item.measuring_unit}
                                            <input
                                                type="hidden"
                                                name={`required_materials[${index}][required_qty]`}
                                                value={item.required_qty}
                                            />
                                        </TableCell>

                                        <TableCell className="border-r font-medium text-blue-600">
                                            {roundToThreeDecimals(
                                                item.balance_qty,
                                            )}{' '}
                                            {item.measuring_unit}
                                            <input
                                                type="hidden"
                                                name={`required_materials[${index}][balance_qty]`}
                                                value={item.balance_qty}
                                            />
                                        </TableCell>
                                    </TableRow>
                                ))
                            ) : (
                                <TableRow>
                                    <TableCell
                                        colSpan={5}
                                        className="text-center"
                                    >
                                        {t('No data available.')}
                                    </TableCell>
                                </TableRow>
                            )}
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
    );
}
