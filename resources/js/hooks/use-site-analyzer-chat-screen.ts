import { sendSiteAnalyzerMessage } from '@/api/site-analyzer.api';
import { Chat, ChatMessage } from '@/types/user';
import { router } from '@inertiajs/react';
import { useChatScreen } from './use-chat-screen';

export function useSiteAnalyzerChatScreen({
    chats,
    initialChatId,
    initialMessages,
}: {
    chats: Chat[];
    initialChatId: string | null;
    initialMessages: ChatMessage[];
}) {
    // hook
    return  useChatScreen({
        chats,
        initialChatId,
        initialMessages,
        sendRequest: (chatId, message) => {
            const url = route('dashboard.user.site-analyzer.message.chat', {
                chat: chatId,
            });

            return sendSiteAnalyzerMessage({ url, message });
        },
        onChatCreated: (newChatId) => {
            router.visit(
                route('dashboard.user.site-analyzer.index', {
                    chat: newChatId,
                }),
                {
                    replace: true,
                    preserveState: false,
                    preserveScroll: true,
                },
            );
        },
    });
}
