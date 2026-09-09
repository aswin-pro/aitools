import Heading from '@/components/heading';
import ChatScreen from '@/components/user/site-analyzer/chat-screen';
import AppLayout from '@/layouts/app/app-layout';
import { BreadcrumbItem } from '@/types';
import { Chat, ChatMessage } from '@/types/user';
import { Head } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

export default function Index({
    chats,
    messages,
}: {
    chats: Chat[];
    messages: ChatMessage[];
}) {
    // breadcrumbs
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: route('dashboard.user.overview'),
        },
        {
            title: 'Site Analyzer',
            href: '#',
        },
    ];

    // transalation
    const { t } = useTranslation();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* Meta Data */}
            <Head title="Site Analyzer" />

            {/* Heading */}
            <Heading
                t={t}
                title="Site Analyzer"
                description="Ask questions about any website and get accurate, context-aware answers, summaries, and insights from its content in seconds."
            />

            {/* Chat Screen */}
            <ChatScreen
                t={t}
                chats={chats}
                messages={messages}
            />
        </AppLayout>
    );
}
