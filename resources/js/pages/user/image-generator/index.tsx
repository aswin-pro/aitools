import { ActionDialog } from '@/components/dialog/action-dialog';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { PaginatedImages } from '@/components/user/image-generator/paginated-images';
import { deleteResource } from '@/helpers/delete-resource';
import AppLayout from '@/layouts/app/app-layout';
import {
    LaravelPagination,
    NavigateParams,
    type BreadcrumbItem,
} from '@/types';
import { AIImage } from '@/types/user';
import { Head, Link, router } from '@inertiajs/react';
import { Sparkles } from 'lucide-react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';

export default function Index({
    images,
}: {
    images: LaravelPagination<AIImage>;
}) {
    // breadcrumbs
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: route('dashboard.user.overview'),
        },
        {
            title: 'Image Generator',
            href: '#',
        },
    ];

    // transalation
    const { t } = useTranslation();

    // states
    const [generationId, setGenerationId] = useState<string | null>(null);
    const [dialogOpen, setDialogOpen] = useState<boolean>(false);
    const [loading, setLoading] = useState<boolean>(false);

    // navigation url
    const navigate = (params: NavigateParams) => {
        router.reload({
            only: ['images'],
            data: params,
        });
    };

    // perform delete
    const performDelete = () => {
        // if generationId empty return
        if (!generationId) return;

        deleteResource({
            url: route('dashboard.user.image-generator.destroy', {
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

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* Meta Data */}
            <Head title="Image Generator" />

            {/* Heading */}
            <div className="-mb-4 flex items-start justify-between">
                {/* Heading */}
                <Heading
                    t={t}
                    title="Image Generator"
                    description="Generate images quickly and easily with AI."
                />

                {/* Generate */}
                <Link
                    href={route('dashboard.user.image-generator.generate')}
                    className="ms-auto"
                >
                    <Button type="button">
                        <Sparkles />
                        {t('Generate')}
                    </Button>
                </Link>
            </div>

            {/* Paginated Images */}
            <PaginatedImages
                t={t}
                images={images.data}
                pageIndex={images.current_page - 1}
                pageSize={images.per_page}
                totalCount={images.total}
                onPageChange={(page) =>
                    navigate({
                        page: page + 1,
                        per_page: images.per_page,
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
                setGenerationId={setGenerationId}
                setDialogOpen={setDialogOpen}
            />

            {/* Dialog */}
            <ActionDialog
                t={t}
                dialogOpen={dialogOpen}
                setDialogOpen={setDialogOpen}
                title="Delete Generated Image"
                description="Are you sure you want to delete this generated image?"
                handleAction={performDelete}
                loading={loading}
            />
        </AppLayout>
    );
}
