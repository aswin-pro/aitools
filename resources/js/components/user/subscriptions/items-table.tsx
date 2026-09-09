import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Transaction } from '@/types/user';

export function ItemsTable({
    t,
    transaction,
}: {
    t: (key: string) => string;
    transaction: Transaction;
}) {
    // Table body
    const items = [
        {
            item: transaction.description,
            quantity: 1,
            amount: transaction.billing_details.subtotal,
        },
    ];

    // Footer rows
    const summaryRows: { label: string; value: string | number }[] = [
        {
            label: 'Subtotal',
            value: transaction.billing_details.subtotal,
        },
        Number(transaction.billing_details.tax_amount) > 0
            ? {
                  label: `${transaction.billing_details.tax_name} (${transaction.billing_details.tax_value}%)`,
                  value: transaction.billing_details.tax_amount,
              }
            : null,
        transaction.billing_details.applied_coupon
            ? {
                  label: `Applied Coupon: ${transaction.billing_details.applied_coupon}`,
                  value: `-${transaction.billing_details.discounted_price}`,
              }
            : null,
        {
            label: 'Total',
            value: transaction.billing_details.invoice_amount,
        },
        {
            label: 'Amount Paid',
            value: transaction.billing_details.invoice_amount,
        },
    ].filter(
        (row): row is { label: string; value: string | number } => row !== null,
    );

    return (
        <div className="border">
            <Table className="shadow-none">
                <TableHeader>
                    <TableRow>
                        <TableHead>{t('Item')}</TableHead>
                        <TableHead className="text-right">
                            {t('Quantity')}
                        </TableHead>
                        <TableHead className="text-right">
                            {t('Amount')}
                        </TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    {items.map((row, index) => (
                        <TableRow key={index}>
                            <TableCell className="font-medium">
                                {row.item}
                            </TableCell>
                            <TableCell className="text-right">
                                {row.quantity}
                            </TableCell>
                            <TableCell className="text-right">
                                {row.amount}
                            </TableCell>
                        </TableRow>
                    ))}

                    {summaryRows.map((row, index) => (
                        <TableRow key={index}>
                            <TableCell colSpan={2} className="text-right">
                                <span className="font-medium">{row.label}</span>
                            </TableCell>

                            <TableCell className="text-right">
                                <span className="font-medium">{row.value}</span>
                            </TableCell>
                        </TableRow>
                    ))}
                </TableBody>
            </Table>
        </div>
    );
}
