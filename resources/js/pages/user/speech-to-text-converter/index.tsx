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

export default function Index({
    conversions,
}: {
    conversions: LaravelPagination<AIContent>;
}) {
    // breadcrumbs
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: route('dashboard.user.overview'),
        },
        {
            title: 'Speech to Text',
            href: '#',
        },
    ];

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
            only: ['conversions'],
            data: params,
        });
    };

    // get data
    const getData = () => {
        return conversions.data.find(
            (conversion) => conversion.generation_id === generationId,
        );
    };

    // perform delete
    const performDelete = () => {
        // if generationId empty return
        if (!generationId) return;

        deleteResource({
            url: route('dashboard.user.speech-to-text.destroy', {
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
                pageIndex: conversions.current_page - 1,
                t,
                setGenerationId,
                setDialogOpen,
                setDialogType,
            }),
        [conversions.current_page, t],
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* Meta Data */}
            <Head title="Speech to Text" />

            {/* Heading */}
            <div className="-mb-4 flex items-start justify-between">
                <Heading
                    t={t}
                    title="Speech to Text"
                    description="Convert speech into accurate text with AI-powered transcription in multiple languages."
                />

                {/* Generate */}
                <Link
                    href={route('dashboard.user.speech-to-text.convert')}
                    className="ms-auto"
                >
                    <Button type="button">
                        <Sparkles />
                        {t('Convert')}
                    </Button>
                </Link>
            </div>

            {/* Data Table */}
            <div className="mt-4">
                <DataTable
                    t={t}
                    columns={columns}
                    data={conversions.data}
                    pageIndex={conversions.current_page - 1}
                    pageSize={conversions.per_page}
                    totalCount={conversions.total}
                    initialSearch={route().params.search ?? ''}
                    onPageChange={(page) =>
                        navigate({
                            page: page + 1,
                            per_page: conversions.per_page,
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
                title="View Converted Text"
                description="Review the converted text."
                size="sm:max-w-xl"
            >
                <ViewContent
                    t={t}
                    content={getData()?.content ?? ''}
                    page="speech-to-text"
                />
            </BasicDialog>

            {/* Delete Dialog */}
            <ActionDialog
                t={t}
                dialogOpen={dialogOpen && dialogType === 'delete'}
                setDialogOpen={setDialogOpen}
                title="Delete Generated Text"
                description="Are you sure you want to delete this generated text?"
                handleAction={performDelete}
                loading={loading}
            />
        </AppLayout>
    );
}
