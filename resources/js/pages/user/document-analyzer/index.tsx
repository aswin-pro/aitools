import Heading from '@/components/heading';
import ChatScreen from '@/components/user/document-analyzer/chat-screen';
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
            title: 'Document Analyzer',
            href: '#',
        },
    ];

    // translation
    const { t } = useTranslation();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* Meta Data */}
            <Head title="Document Analyzer" />

            {/* Heading */}
            <Heading
                t={t}
                title="Document Analyzer"
                description="Ask questions, search documents, and get accurate, context-aware answers from your documentation in seconds."
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
