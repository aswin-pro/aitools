import { ActionDialog } from '@/components/dialog/action-dialog';
import { BasicDialog } from '@/components/dialog/dialog';
import Heading from '@/components/heading';
import { DataTable } from '@/components/table/data-table';
import { Button } from '@/components/ui/button';
import { ViewContent } from '@/components/user/common/view-content';
import { EditContent } from '@/components/user/content-generator/edit-content';
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

export default function Index({
    contents,
}: {
    contents: LaravelPagination<AIContent>;
}) {
    // breadcrumbs
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: route('dashboard.user.overview'),
        },
        {
            title: 'Content Generator',
            href: '#',
        },
    ];

    // transalation
    const { t } = useTranslation();

    // states
    const [dialogOpen, setDialogOpen] = useState<boolean>(false);
    const [dialogType, setDialogType] = useState<'view' | 'update' | 'delete'>(
        'view',
    );
    const [generationId, setGenerationId] = useState<string | null>(null);
    const [loading, setLoading] = useState<boolean>(false);

    // navigation url
    const navigate = (params: NavigateParams) => {
        router.reload({
            only: ['contents'],
            data: params,
        });
    };

    // get data
    const getData = () => {
        return contents.data.find(
            (content) => content.generation_id === generationId,
        );
    };

    // perform delete
    const performDelete = () => {
        // return if not generation id
        if (!generationId) return;

        // delete
        deleteResource({
            url: route('dashboard.user.content-generator.destroy', {
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
                pageIndex: contents.current_page - 1,
                t,
                setDialogOpen,
                setDialogType,
                setGenerationId,
            }),
        [contents.current_page, t],
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* Meta Data */}
            <Head title="Content Generator" />

            {/* Heading */}
            <div className="-mb-4 flex items-start justify-between">
                <Heading
                    t={t}
                    title="Content Generator"
                    description="Generate different types of content quickly and efficiently with AI"
                />

                {/* Generate */}
                <Link
                    href={route('dashboard.user.content-generator.templates')}
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
                    data={contents.data}
                    pageIndex={contents.current_page - 1}
                    pageSize={contents.per_page}
                    totalCount={contents.total}
                    initialSearch={route().params.search ?? ''}
                    onPageChange={(page) =>
                        navigate({
                            page: page + 1,
                            per_page: contents.per_page,
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
                dialogOpen={
                    dialogOpen &&
                    (dialogType === 'view' || dialogType === 'update')
                }
                setDialogOpen={setDialogOpen}
                title={
                    dialogType === 'view'
                        ? 'View Generated Content'
                        : 'Edit Generated Content'
                }
                description={
                    dialogType === 'view'
                        ? 'Review the generated content.'
                        : 'Make changes to the generated content and save your updates.'
                }
                size="sm:max-w-xl"
            >
                {dialogType === 'view' ? (
                    <ViewContent
                        t={t}
                        content={getData()?.content ?? ''}
                        page="content-generator"
                    />
                ) : (
                    <EditContent t={t} content={getData() as AIContent} />
                )}
            </BasicDialog>

            {/* Delete Dialog */}
            <ActionDialog
                t={t}
                dialogOpen={dialogOpen && dialogType === 'delete'}
                setDialogOpen={setDialogOpen}
                title="Delete Generated Content"
                description="Are you sure you want to delete this generated content?"
                handleAction={performDelete}
                loading={loading}
            />
        </AppLayout>
    );
}
