import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableFooter,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { formatCurrency } from '@/helpers/format-number';
import { Purchase } from '@/types';
import { Printer } from 'lucide-react';
import { useTranslation } from 'react-i18next';

export default function PurchaseInvoice({ purchase }: { purchase: Purchase }) {
    // i18n
    const { t } = useTranslation();

    // return
    return (
        <div className="flex w-full flex-col items-center justify-center">
            <div className="flex justify-end w-full max-w-4xl mb-4">
                <Button onClick={() => window.print()}>
                    <Printer className="h-4 w-4" />
                    {t('Print')}
                </Button>
            </div>

            <div
                id="print-area"
                className="print:absolute print:inset-0 print:w-full w-full max-w-4xl"
            >
                <Card className='print:border-none'>
                    <CardContent>
                        {/* Header */}
                        <div className="space-y-1.5 border-b pb-4 text-end">
                            <h2 className="text-2xl font-semibold">
                                {t('Purchase Invoice')}
                            </h2>
                            <p className="text-lg">
                                {t('Invoice #: ')}
                                {purchase.id}
                            </p>
                            <p className="text-base">
                                {t('Date: ')} {purchase.formatted_created_at}
                            </p>
                        </div>

                        {/* Seller Information */}
                        <div className="mt-5">
                            <h3 className="text-lg font-bold">
                                {t('Seller Information')}
                            </h3>

                            {/* Supplier name and address */}
                            <div className="mt-3 space-y-1.5">
                                <p>{purchase.supplier_name}</p>
                                <p>{purchase.billing_address}</p>
                                <p>
                                    {purchase.supplier_phone}
                                </p>
                            </div>
                        </div>

                        {/* Buyer Information */}
                        <div className="mt-5">
                            <h3 className="text-lg font-bold">
                                {t('Buyer Information')}
                            </h3>

                            {/* Buyer name and address */}
                            <div className="mt-3 space-y-1.5">
                                <p>{purchase.company.company_name}</p>
                                <p>{purchase.company.address}</p>
                                <p>
                                    {purchase.company.mobile_number}
                                </p>
                            </div>
                        </div>

                        {/* Invoice Details */}
                        <div className="mt-5">
                            <h3 className="text-lg font-bold">
                                {t('Invoice Details')}
                            </h3>

                            {/* Purchase Items */}
                            <Table className='border mt-4'>
                                <TableHeader className="bg-secondary">
                                    <TableRow>
                                        <TableHead className="border-r">
                                            {t('S.No')}
                                        </TableHead>
                                        <TableHead className="border-r">
                                            {t('Description')}
                                        </TableHead>
                                        <TableHead className="border-r">
                                            {t('Quantity')}
                                        </TableHead>
                                        <TableHead className="border-r">
                                            {t('Unit Price')}
                                        </TableHead>
                                        <TableHead className="border-r">
                                            {t('Discount')}
                                        </TableHead>
                                        <TableHead className="border-r">
                                            {t('Tax (%)')}
                                        </TableHead>
                                        <TableHead className="border-r">
                                            {t('Tax Amount')}
                                        </TableHead>
                                        <TableHead>{t('Total')}</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {purchase.purchase_items.map(
                                        (item, index) => (
                                            <TableRow key={index}>
                                                <TableCell className="border-r">
                                                    {index + 1}
                                                </TableCell>
                                                <TableCell className="border-r">
                                                    {item.product_name}
                                                </TableCell>
                                                <TableCell className="border-r">
                                                    {item.quantity}
                                                </TableCell>
                                                <TableCell className="border-r">
                                                    {formatCurrency(
                                                        item.unit_price,
                                                    )}
                                                </TableCell>
                                                <TableCell className="border-r">
                                                    {formatCurrency(
                                                        item.discount,
                                                    )}
                                                </TableCell>
                                                <TableCell className="border-r">
                                                    {item.tax_percentage}
                                                </TableCell>
                                                <TableCell className="border-r">
                                                    {formatCurrency(
                                                        item.tax_amount,
                                                    )}
                                                </TableCell>
                                                <TableCell>
                                                    {formatCurrency(
                                                        item.total,
                                                    )}
                                                </TableCell>
                                            </TableRow>
                                        ),
                                    )}
                                </TableBody>
                                <TableFooter className="bg-transparent">
                                    <TableRow>
                                        <TableCell
                                            colSpan={7}
                                            className="border-r bg-muted/50 text-right"
                                        >
                                            {t('Subtotal: ')}
                                        </TableCell>
                                        <TableCell>
                                            {formatCurrency(
                                                purchase.subtotal,
                                            )}
                                        </TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell
                                            colSpan={7}
                                            className="border-r bg-muted/50 text-right"
                                        >
                                            {t('Discount: ')}
                                        </TableCell>
                                        <TableCell>
                                            {formatCurrency(
                                                purchase.discount,
                                            )}
                                        </TableCell>
                                    </TableRow>
                                    <TableRow>
                                        <TableCell
                                            colSpan={7}
                                            className="border-r bg-muted/50 text-right"
                                        >
                                            {t('Grand Total: ')}
                                        </TableCell>
                                        <TableCell>
                                            {formatCurrency(
                                                purchase.grand_total,
                                            )}
                                        </TableCell>
                                    </TableRow>
                                </TableFooter>
                            </Table>
                        </div>

                        {/* Footer Texts */}
                        <div className="mt-10 mb-2 space-y-2 text-center">
                            <p className="text-sm text-gray-400">
                                {t('Thank you for your business.')}
                            </p>
                            <p className="text-sm text-gray-400">
                                {t(
                                    'If you have any queries, please contact us.',
                                )}
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    );
}
