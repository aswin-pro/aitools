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
import { Sale } from '@/types';
import { Printer } from 'lucide-react';
import { useTranslation } from 'react-i18next';

export default function SaleInvoice({ sale }: { sale: Sale }) {
    // i18n
    const { t } = useTranslation();

    // return
    return (
        <div className="flex w-full flex-col items-center justify-center">
            <div className="mb-4 flex w-full max-w-4xl justify-end">
                <Button onClick={() => window.print()}>
                    <Printer className="h-4 w-4" />
                    {t('Print')}
                </Button>
            </div>

            <div
                id="print-area"
                className="w-full max-w-4xl print:absolute print:inset-0 print:w-full"
            >
                <Card className="print:border-none">
                    <CardContent>
                        {/* Header */}
                        <div className="space-y-1.5 border-b pb-4 text-end">
                            <h2 className="text-2xl font-semibold">
                                {t('Sales Invoice')}
                            </h2>
                            <p className="text-lg">
                                {t('Invoice #: ')}
                                {sale.id}
                            </p>
                            <p className="text-base">
                                {t('Date: ')} {sale.formatted_created_at}
                            </p>
                        </div>

                        {/* Customer Information */}
                        <div className="mt-5">
                            <h3 className="text-lg font-bold">
                                {t('Customer Information')}
                            </h3>

                            {/* customer name and address */}
                            <div className="mt-3 space-y-1.5">
                                <p>{sale.customer_name}</p>
                                <p>{sale.billing_address}</p>
                                <p>
                                    {sale.customer_phone}
                                </p>
                            </div>
                        </div>

                        {/* Seller Information */}
                        <div className="mt-5">
                            <h3 className="text-lg font-bold">
                                {t('Seller Information')}
                            </h3>

                            {/* Buyer name and address */}
                            <div className="mt-3 space-y-1.5">
                                <p>{sale.company.company_name}</p>
                                <p>{sale.company.address}</p>
                                <p>
                                    {sale.company.mobile_number}
                                </p>
                            </div>
                        </div>

                        {/* Invoice Details */}
                        <div className="mt-5">
                            <h3 className="text-lg font-bold">
                                {t('Invoice Details')}
                            </h3>

                            {/* Sale Items */}
                            <Table className="mt-4 border">
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
                                    {sale.sale_items.map((item, index) => (
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
                                                {formatCurrency(item.discount)}
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
                                                {formatCurrency(item.total)}
                                            </TableCell>
                                        </TableRow>
                                    ))}
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
                                            {formatCurrency(sale.subtotal)}
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
                                            {formatCurrency(sale.discount)}
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
                                            {formatCurrency(sale.grand_total)}
                                        </TableCell>
                                    </TableRow>
                                </TableFooter>
                            </Table>
                        </div>

                        {/* Transportation Details */}
                        {sale.transportation_details?.lr_no && (
                            <div className="mt-5">
                                <p className="text-sm">
                                    <span className="font-medium">
                                        {t('LR / Consignment No: ')}{' '}
                                    </span>
                                    {sale.transportation_details.lr_no}
                                </p>
                            </div>
                        )}

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
