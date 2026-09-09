import { sendPersonalizedChatMessage } from '@/api/personalized-chat.api';
import { Chat, ChatMessage } from '@/types/user';
import { router } from '@inertiajs/react';
import { useChatScreen } from './use-chat-screen';

export function usePersonalizedChatScreen({
    assistantId,
    chats,
    initialChatId,
    initialMessages,
}: {
    assistantId: string;
    chats: Chat[];
    initialChatId: string | null;
    initialMessages: ChatMessage[];
}) {
    // hook
    return useChatScreen({
        chats,
        initialChatId,
        initialMessages,
        sendRequest: (chatId, message) => {
            const url = chatId
                ? route('dashboard.user.personalized-chat.message.chat', {
                      assistant: assistantId,
                      chat: chatId,
                  })
                : route('dashboard.user.personalized-chat.message', {
                      assistant: assistantId,
                  });

            return sendPersonalizedChatMessage({ url, message });
        },
        onChatCreated: (newChatId) => {
            router.visit(
                route('dashboard.user.personalized-chat.chat', {
                    assistant: assistantId,
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
