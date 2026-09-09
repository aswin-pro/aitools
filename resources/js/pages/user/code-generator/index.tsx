import { ActionDialog } from '@/components/dialog/action-dialog';
import { BasicDialog } from '@/components/dialog/dialog';
import Heading from '@/components/heading';
import { DataTable } from '@/components/table/data-table';
import { Button } from '@/components/ui/button';
import { ViewContent } from '@/components/user/common/view-content';
import { deleteResource } from '@/helpers/delete-resource';
import AppLayout from '@/layouts/app/app-layout';
import {
    LaravelPagination,
    NavigateParams,
    type BreadcrumbItem,
} from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { Sparkles } from 'lucide-react';
import { useMemo, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { getColumns } from './columns';
import { AIContent } from '@/types/user';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard.user.overview'),
    },
    {
        title: 'Code Generator',
        href: '#',
    },
];

export default function Index({
    codes,
}: {
    codes: LaravelPagination<AIContent>;
}) {
    // transalation
    const { t } = useTranslation();

    // states
    const [generationId, setGenerationId] = useState<string | null>(null);
    const [dialogOpen, setDialogOpen] = useState<boolean>(false);
    const [dialogType, setDialogType] = useState<'view' | 'delete'>('view');
    const [loading, setLoading] = useState<boolean>(false);

    // navigation url
    const navigate = (params: NavigateParams) => {
        router.reload({
            only: ['codes'],
            data: params,
        });
    };

    // get data
    const getData = () => {
        return codes.data.find((code) => code.generation_id === generationId);
    };

    // perform delete
    const performDelete = () => {
        // if generationId empty return
        if (!generationId) return;

        // delete
        deleteResource({
            url: route('dashboard.user.code-generator.destroy', {
                id: generationId,
            }),
            t,
            onStart: () => setLoading(true),
            onFinish: () => {
                setLoading(false);
                setDialogOpen(false);
            },
        });
    };

    // data table columns
    const columns = useMemo(
        () =>
            getColumns({
                pageIndex: codes.current_page - 1,
                t,
                setGenerationId,
                setDialogOpen,
                setDialogType,
            }),
        [codes.current_page, t],
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* Meta Data */}
            <Head title="Code Generator" />

            {/* Heading */}
            <div className="-mb-4 flex items-start justify-between">
                <Heading
                    t={t}
                    title="Code Generator"
                    description="Generate code quickly and easily with AI."
                />

                {/* Generate */}
                <Link
                    href={route('dashboard.user.code-generator.generate')}
                    className="ms-auto"
                >
                    <Button type="button">
                        <Sparkles />
                        {t('Generate')}
                    </Button>
                </Link>
            </div>

            {/* Data Table */}
            <div className="mt-4">
                <DataTable
                    t={t}
                    columns={columns}
                    data={codes.data}
                    pageIndex={codes.current_page - 1}
                    pageSize={codes.per_page}
                    totalCount={codes.total}
                    initialSearch={route().params.search ?? ''}
                    onPageChange={(page) =>
                        navigate({
                            page: page + 1,
                            per_page: codes.per_page,
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

            {/* Dialog */}
            <BasicDialog
                t={t}
                dialogOpen={dialogOpen && dialogType === 'view'}
                setDialogOpen={setDialogOpen}
                title="View Generated Code"
                description="Review the generated code."
                size="sm:max-w-4xl"
            >
                <ViewContent
                    t={t}
                    content={getData()?.content ?? ''}
                    page="code-generator"
                />
            </BasicDialog>

            {/* Delete Dialog */}
            <ActionDialog
                t={t}
                dialogOpen={dialogOpen && dialogType === 'delete'}
                setDialogOpen={setDialogOpen}
                title="Delete Generated Code"
                description="Are you sure you want to delete this generated code?"
                handleAction={performDelete}
                loading={loading}
            />
        </AppLayout>
    );
}
