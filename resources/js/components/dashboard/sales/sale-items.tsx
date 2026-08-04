import DynamicTableField from '@/components/form/dynamic-table-fields';
import { Button } from '@/components/ui/button';
import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
    InputGroupText,
} from '@/components/ui/input-group';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    roundToThreeDecimals,
    roundToTwoDecimals,
} from '@/helpers/format-number';
import { FieldType, Product, Sale, SaleItem } from '@/types';
import { Plus, Trash2 } from 'lucide-react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';

export default function SalesItems({
    products,
    sale,
}: {
    products: Product[];
    sale?: Sale;
}) {
    // i18n
    const { t } = useTranslation();

    // prepare items
    const [items, setItems] = useState<SaleItem[]>(
        sale?.sale_items?.length
            ? sale.sale_items.map((item) => ({
                  ...item,
                  quantity: roundToThreeDecimals(item.quantity),
                  unit_price: roundToTwoDecimals(item.unit_price),
                  discount: roundToTwoDecimals(item.discount),
                  tax_percentage: roundToTwoDecimals(item.tax_percentage),
                  tax_amount: roundToTwoDecimals(item.tax_amount ?? 0),
              }))
            : [
                  {
                      product_id: '',
                      quantity: 1,
                      unit_price: 0,
                      discount: 0,
                      tax_percentage: 0,
                  },
              ],
    );

    // get measurement unit
    const getUnit = (productId: string) =>
        products.find((p) => p.product_id === productId)?.measurement_unit
            ?.unit;

    // add row
    const addRow = () => {
        setItems((prev) => [
            ...prev,
            {
                product_id: '',
                quantity: 1,
                unit_price: 0,
                discount: 0,
                tax_percentage: 0,
                tax_amount: 0,
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
        field: keyof SaleItem,
        value: string | number,
    ) => {
        setItems((prev) =>
            prev.map((item, i) => {
                if (i !== index) return item;

                if (field === 'product_id') {
                    const product = products.find(
                        (p) => p.product_id.toString() === value,
                    );

                    if (!product) {
                        return {
                            ...item,
                            product_id: value as string,
                        };
                    }

                    return {
                        ...item,
                        product_id: value as string,
                        unit_price: roundToTwoDecimals(product.selling_price),
                        tax_percentage: roundToTwoDecimals(
                            product.tax_percentage,
                        ),
                    };
                }

                return {
                    ...item,
                    [field]: value,
                };
            }),
        );
    };

    // whole discount
    const [wholeDiscount, setWholeDiscount] = useState<number>(
        roundToTwoDecimals(sale?.discount ?? 0),
    );

    // get subTotal
    const getSubTotal = (item: SaleItem) =>
        item.quantity * item.unit_price - item.discount;

    // get tax amount
    const getTaxAmount = (item: SaleItem) => {
        return roundToTwoDecimals(
            (getSubTotal(item) * item.tax_percentage) / 100,
        );
    };

    // get row total
    const getRowTotal = (item: SaleItem) => {
        return roundToTwoDecimals(getSubTotal(item) + getTaxAmount(item));
    };

    // get subtotal
    const subtotal = roundToTwoDecimals(
        items.reduce((sum, item) => sum + getRowTotal(item), 0),
    );

    // grand total
    const grandTotal = roundToTwoDecimals(subtotal - wholeDiscount);

    const columns: FieldType<SaleItem>[] = [
        {
            id: 'product_id',
            fieldType: 'select',
            props: {
                placeholder: t('Select Product'),
                options: products.map((product) => ({
                    label: product.product_name,
                    value: product.product_id.toString(),
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
                max: (item) =>
                    products.find((p) => p.product_id === item.product_id)
                        ?.inventory_sum_stock,
                step: '0.001',
                required: true,
            },
            inputGroup: {
                align: 'inline-end',
                content: (item) => getUnit(item.product_id),
            },
            value: (item) => roundToThreeDecimals(item.quantity),
        },
        {
            id: 'unit_price',
            fieldType: 'input-group',
            props: {
                type: 'number',
                min: 0,
                step: '0.01',
                required: true,
            },
            inputGroup: {
                align: 'inline-start',
                content: '₹',
            },
            value: (item) => roundToTwoDecimals(item.unit_price),
        },
        {
            id: 'discount',
            fieldType: 'input-group',
            props: {
                type: 'number',
                min: 0,
                step: '0.01',
                required: true,
            },
            inputGroup: {
                align: 'inline-start',
                content: '₹',
            },
            value: (item) => roundToTwoDecimals(item.discount),
        },
        {
            id: 'tax_percentage',
            fieldType: 'input-group',
            props: {
                min: 0,
                type: 'number',
                step: '0.01',
                readOnly: true,
                required: true,
            },
            inputGroup: {
                align: 'inline-start',
                content: '%',
            },
            value: (item) => roundToTwoDecimals(item.tax_percentage),
        },
        {
            id: 'tax_amount',
            fieldType: 'input-group',
            props: {
                min: 0,
                type: 'number',
                step: '0.01',
                readOnly: true,
                required: true,
            },
            inputGroup: {
                align: 'inline-start',
                content: '₹',
            },
            value: (item) => getTaxAmount(item),
        },
        {
            id: 'total',
            fieldType: 'input-group',
            props: {
                min: 0,
                type: 'number',
                step: '0.01',
                readOnly: true,
                required: true,
            },
            inputGroup: {
                align: 'inline-start',
                content: '₹',
            },
            value: (item) => getRowTotal(item),
        },
    ];

    // summary rows
    const summaryRows = [
        {
            name: 'subtotal',
            label: t('Subtotal'),
            value: subtotal,
            readOnly: true,
        },
        {
            name: 'whole_discount',
            label: t('Discount'),
            value: wholeDiscount,
            readOnly: false,
            onChange: (value: string) => setWholeDiscount(Number(value)),
        },
        {
            name: 'grand_total',
            label: t('Grand Total'),
            value: grandTotal,
            readOnly: true,
        },
    ];

    // return
    return (
        <div className="mt-6">
            <h2 className="mb-6 border-b pb-2 text-xl font-medium">
                {t('Sale Items')}
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
                            <TableHead className="w-32 min-w-32 border-r">
                                {t('Unit Price')}
                            </TableHead>
                            <TableHead className="w-32 min-w-32 border-r">
                                {t('Discount')}
                            </TableHead>
                            <TableHead className="w-24 min-w-24 border-r">
                                {t('Tax (%)')}
                            </TableHead>
                            <TableHead className="w-32 min-w-32 border-r">
                                {t('Tax Amount')}
                            </TableHead>
                            <TableHead className="w-36 min-w-36 border-r">
                                {t('Total')}
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

            {/* Total */}
            <div className="mt-4 flex w-full items-end justify-end">
                <div className="w-full md:w-[56%] lg:w-[26%]">
                    <Table>
                        <TableBody>
                            {summaryRows.map((row) => (
                                <TableRow key={row.name}>
                                    <TableCell className="border-b">
                                        {row.label}
                                    </TableCell>
                                    <TableCell className="border-b">
                                        :
                                    </TableCell>
                                    <TableCell className="border-b">
                                        <InputGroup>
                                            <InputGroupInput
                                                className="no-spinner"
                                                name={row.name}
                                                type="number"
                                                min={
                                                    row.readOnly ? undefined : 0
                                                }
                                                step={
                                                    row.readOnly
                                                        ? undefined
                                                        : '0.01'
                                                }
                                                value={row.value}
                                                readOnly={row.readOnly}
                                                onChange={
                                                    row.onChange
                                                        ? (e) =>
                                                              row.onChange!(
                                                                  e.target
                                                                      .value,
                                                              )
                                                        : undefined
                                                }
                                            />

                                            <InputGroupAddon align="inline-start">
                                                <InputGroupText>
                                                    ₹
                                                </InputGroupText>
                                            </InputGroupAddon>
                                        </InputGroup>
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
    );
}
