import { sendDocumentAnalyzerMessage } from '@/api/document-assistant.api';
import { Chat, ChatMessage } from '@/types/user';
import { router } from '@inertiajs/react';
import { useState } from 'react';
import { useChatScreen } from './use-chat-screen';

export function useDocumentAnalyzerChatScreen({
    chats,
    initialChatId,
    initialMessages,
}: {
    chats: Chat[];
    initialChatId: string | null;
    initialMessages: ChatMessage[];
}) {
    // file
    const [file, setFile] = useState<string | null>(null);

    // hook
    const chatState = useChatScreen({
        chats,
        initialChatId,
        initialMessages,
        sendRequest: (chatId, message) => {
            const url = chatId
                ? route('dashboard.user.document-analyzer.message.chat', {
                      chat: chatId,
                  })
                : route('dashboard.user.document-analyzer.message');

            return sendDocumentAnalyzerMessage({ url, file, message });
        },
        onChatCreated: (newChatId) => {
            router.visit(
                route('dashboard.user.document-analyzer.index', {
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

    return { ...chatState, file, setFile };
}
