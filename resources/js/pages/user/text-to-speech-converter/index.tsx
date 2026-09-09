import { ActionDialog } from '@/components/dialog/action-dialog';
import { BasicDialog } from '@/components/dialog/dialog';
import Heading from '@/components/heading';
import { DataTable } from '@/components/table/data-table';
import { AudioPlayer } from '@/components/ui/audio-player';
import { Button } from '@/components/ui/button';
import { assetUrl } from '@/helpers/asset-url';
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
            title: 'Text to Speech',
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

        // delete
        deleteResource({
            url: route('dashboard.user.text-to-speech.destroy', {
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
            <Head title="Text to Speech" />

            {/* Heading */}
            <div className="-mb-4 flex items-start justify-between">
                <Heading
                    t={t}
                    title="Text to Speech"
                    description="Convert text into natural, high-quality speech with AI in multiple voices and languages."
                />

                {/* Generate */}
                <Link
                    href={route('dashboard.user.text-to-speech.convert')}
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
                title="Preview"
                description="Play the generated audio and review the output."
                size="sm:max-w-xl"
            >
                {/* audio player */}
                <AudioPlayer
                    t={t}
                    name={getData()?.name ?? ''}
                    src={assetUrl(getData()?.content ?? '')}
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
