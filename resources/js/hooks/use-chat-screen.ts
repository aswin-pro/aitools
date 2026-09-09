import { showDynamicToast } from '@/helpers/show-dynamic-toast';
import { Chat, ChatMessage } from '@/types/user';
import { useCallback, useMemo, useState } from 'react';

export interface SendMessageResponse {
    chat: Chat;
    chat_id: string;
    user_message: ChatMessage;
    assistant_message: ChatMessage;
}

interface UseChatWindowParams {
    chats: Chat[];
    initialChatId: string | null;
    initialMessages: ChatMessage[];
    sendRequest: (
        chatId: string | null,
        message: string,
    ) => Promise<SendMessageResponse>;
    onChatCreated?: (chatId: string) => void;
}

export function useChatScreen({
    chats,
    initialChatId,
    initialMessages,
    sendRequest,
    onChatCreated,
}: UseChatWindowParams) {
    const [conversation, setConversation] =
        useState<ChatMessage[]>(initialMessages);
    const [chatId, setChatId] = useState<string | null>(initialChatId);

    const [input, setInput] = useState('');
    const [sending, setSending] = useState(false);
    const [search, setSearch] = useState('');

    const filteredChats = useMemo(() => {
        const term = search.trim().toLowerCase();
        if (!term) return chats;

        return chats.filter((chat) =>
            chat.chat_title.toLowerCase().includes(term),
        );
    }, [chats, search]);

    const sendMessage = useCallback(async () => {
        const text = input.trim();
        if (!text || sending) return;

        setSending(true);

        const optimisticMessage: ChatMessage = {
            id: Date.now(),
            chat_message_id: `temp-${Date.now()}`,
            chat_id: chatId ?? '',
            responsed_by: 'user',
            chat_message: text,
            formatted_created_at: '',
        };

        setConversation((prev) => [...prev, optimisticMessage]);
        setInput('');

        try {
            const data = await sendRequest(chatId, text);

            setConversation((prev) => [
                ...prev.filter(
                    (m) =>
                        m.chat_message_id !== optimisticMessage.chat_message_id,
                ),
                data.user_message,
                data.assistant_message,
            ]);

            if (!chatId) {
                setChatId(data.chat_id);
                onChatCreated?.(data.chat_id);
            }
        } catch (error) {
            const message =
                error instanceof Error
                    ? error.message
                    : 'Something went wrong.';

            setConversation((prev) =>
                prev.filter(
                    (m) =>
                        m.chat_message_id !== optimisticMessage.chat_message_id,
                ),
            );

            setInput(text);
            showDynamicToast(message);
        } finally {
            setSending(false);
        }
    }, [chatId, input, sending, sendRequest, onChatCreated]);

    return {
        chatId,
        conversation,
        filteredChats,
        input,
        sending,
        search,
        setInput,
        setSearch,
        sendMessage,
    };
}
