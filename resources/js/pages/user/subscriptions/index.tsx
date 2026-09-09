import Heading from '@/components/heading';
import { DataTable } from '@/components/table/data-table';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app/app-layout';
import {
    LaravelPagination,
    NavigateParams,
    type BreadcrumbItem,
} from '@/types';
import { Transaction } from '@/types/user';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { CalendarPlus } from 'lucide-react';
import { useEffect, useMemo, useRef } from 'react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import { getColumns } from './columns';

export default function Index({
    transactions,
}: {
    transactions: LaravelPagination<Transaction>;
}) {
    // breadcrumbs
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: route('dashboard.user.overview'),
        },
        {
            title: 'Subscriptions',
            href: '#',
        },
    ];

    // translation
    const { t } = useTranslation();

    // navigation url
    const navigate = (params: NavigateParams) => {
        router.reload({
            only: ['transactions'],
            data: params,
        });
    };

    const { flash } = usePage<{
        flash: {
            success?: string;
            error?: string;
        };
    }>().props;

    const shown = useRef(false);

    useEffect(() => {
        if (shown.current) return;

        if (flash.success || flash.error) {
            shown.current = true;

            if (flash.success) toast.success(flash.success);
            if (flash.error) toast.error(flash.error);
        }
    }, [flash.success, flash.error]);

    // data table columns
    const columns = useMemo(
        () =>
            getColumns({
                pageIndex: transactions.current_page - 1,
                t,
            }),
        [transactions.current_page, t],
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* Meta Data */}
            <Head title="Subscriptions" />

            <div className="-mb-4 flex items-start justify-between">
                {/* heading */}
                <Heading
                    t={t}
                    title="Subscriptions"
                    description="View and manage your active subscriptions"
                />

                {/* Plans */}
                <Link
                    href={route('dashboard.user.subscriptions.plans')}
                    className="ms-auto"
                >
                    <Button type="button">
                        <CalendarPlus />
                        {t('Plans')}
                    </Button>
                </Link>
            </div>

            {/* Data Table */}
            <div className="mt-4">
                {/* Table */}
                <DataTable
                    t={t}
                    columns={columns}
                    data={transactions.data}
                    pageIndex={transactions.current_page - 1}
                    pageSize={transactions.per_page}
                    totalCount={transactions.total}
                    initialSearch={route().params.search ?? ''}
                    onPageChange={(page) =>
                        navigate({
                            page: page + 1,
                            per_page: transactions.per_page,
                            search: route().params.search,
                        })
                    }
                    onPageSizeChange={(size) =>
                        navigate({
                            page: 1,
                            per_page: size,
                            search: route().params.search,
                        })
                    }
                    onSearch={(search) =>
                        navigate({
                            page: 1,
                            search,
                        })
                    }
                />
            </div>
        </AppLayout>
    );
}
