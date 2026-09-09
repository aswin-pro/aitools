import Heading from '@/components/heading';
import { PaginatedAssistants } from '@/components/user/personalized-chat/paginated-assistants';
import AppLayout from '@/layouts/app/app-layout';
import {
    LaravelPagination,
    NavigateParams,
    type BreadcrumbItem,
} from '@/types';
import { ChatAssistant } from '@/types/user';
import { Head, router } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

export default function Index({
    assistants,
}: {
    assistants: LaravelPagination<ChatAssistant>;
}) {
    // breadcrumbs
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: route('dashboard.user.overview'),
        },
        {
            title: 'Personalized Chat',
            href: '#',
        },
    ];

    // transalation
    const { t } = useTranslation();

    // navigation url
    const navigate = (params: NavigateParams) => {
        router.reload({
            only: ['assistants'],
            data: params,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* Meta Data */}
            <Head title="Personalized Chat" />

            {/* Heading */}
            <Heading
                t={t}
                title="Personalized Chat"
                description="Experience conversations that feel natural, engaging, and tailored to your unique needs, preferences, and goals."
            />

            {/* Paginated Assistants */}
            <PaginatedAssistants
                t={t}
                assistants={assistants.data}
                pageIndex={assistants.current_page - 1}
                pageSize={assistants.per_page}
                totalCount={assistants.total}
                onPageChange={(page) =>
                    navigate({
                        page: page + 1,
                        per_page: assistants.per_page,
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
            />
        </AppLayout>
    );
}
