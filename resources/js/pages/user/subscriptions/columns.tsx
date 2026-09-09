import { CustomBadge } from '@/components/table/badge';
import { Button } from '@/components/ui/button';
import { Transaction } from '@/types/user';
import { Link } from '@inertiajs/react';
import { ColumnDef } from '@tanstack/react-table';
import { ScrollText } from 'lucide-react';

export const getColumns = ({
    pageIndex,
    t,
}: {
    pageIndex: number;
    t: (key: string) => string;
}): ColumnDef<Transaction>[] => [
    {
        accessorKey: t('S.No'),
        header: t('S.No'),
        cell: ({ row }) => pageIndex * 10 + row.index + 1,
    },
    {
        accessorKey: t('Transaction Date'),
        header: t('Transaction Date'),
        cell: ({ row }) => row.original.formatted_created_at,
    },
    {
        accessorKey: t('Payment ID'),
        header: t('Payment ID'),
        cell: ({ row }) => row.original.transaction_id,
    },
    {
        accessorKey: t('Plan'),
        header: t('Plan'),
        cell: ({ row }) => row.original.plan?.name ?? '-',
    },
    {
        accessorKey: t('Payment Mode'),
        header: t('Payment Mode'),
        cell: ({ row }) => row.original.payment_gateway_name,
    },
    {
        accessorKey: t('Amount'),
        header: t('Amount'),
        cell: ({ row }) => row.original.formatted_amount,
    },
    {
        accessorKey: t('Payment Status'),
        header: t('Payment Status'),
        cell: ({ row }) =>
            CustomBadge(
                {
                    PENDING: t('Pending'),
                    SUCCESS: t('Paid'),
                    FAILED: t('Failed'),
                    CANCELLED: t('Cancelled'),
                }[row.original.payment_status],
                {
                    PENDING: 'bg-orange-500 text-white dark:bg-orange-800',
                    SUCCESS: 'bg-green-500 text-white dark:bg-green-800',
                    FAILED: 'bg-red-500 text-white dark:bg-red-800',
                    CANCELLED: 'bg-red-500 text-white dark:bg-red-800',
                }[row.original.payment_status],
            ),
    },
    {
        accessorKey: t('Action'),
        header: t('Action'),
        cell: ({ row }) => {
            return (
                <div className="text-center">
                    {row.original.payment_status === 'SUCCESS' &&
                    row.original.transaction_amount !== 0.0 ? (
                        <Link
                            href={route(
                                'dashboard.user.subscriptions.invoice',
                                {
                                    id: row.original.id,
                                },
                            )}
                        >
                            <Button type="button" variant="outline" size="icon">
                                <ScrollText />
                            </Button>
                        </Link>
                    ) : (
                        '-'
                    )}
                </div>
            );
        },
    },
];
