import Heading from '@/components/heading';
import ChatScreen from '@/components/user/personalized-chat/chat-screen';
import AppLayout from '@/layouts/app/app-layout';
import { BreadcrumbItem } from '@/types';
import { Chat, ChatAssistant, ChatMessage } from '@/types/user';
import { Head } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

export default function Index({
    assistant,
    chats,
    messages,
}: {
    assistant: ChatAssistant;
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
            title: 'Personalized Chat',
            href: route('dashboard.user.personalized-chat.index'),
        },
        {
            title: assistant.chat_assistant_name,
            href: '#',
        },
    ];

    // transalation
    const { t } = useTranslation();

    // assistant id
    const assistantId = route().params.assistant as string;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* Meta Data */}
            <Head title={'Chat with ' + assistant.chat_assistant_name} />

            {/* Heading */}
            <Heading
                t={t}
                title={'Chat with ' + assistant.chat_assistant_name}
                description={assistant.chat_assistant_description}
            />

            {/* Chat Screen */}
            <ChatScreen
                t={t}
                assistant={assistant}
                assistantId={assistantId}
                chats={chats}
                messages={messages}
            />
        </AppLayout>
    );
}
