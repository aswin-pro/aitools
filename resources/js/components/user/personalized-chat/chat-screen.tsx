import { deleteResource } from '@/helpers/delete-resource';
import { usePersonalizedChatScreen } from '@/hooks/use-personalized-chat-screen';
import { Chat, ChatAssistant, ChatMessage } from '@/types/user';
import { ChatLayout } from '../chat/chat-layout';
import { ChatMessages } from '../chat/chat-messages';
import { ChatSidebar } from '../chat/chat-side-bar';
import ChatTitleForm from '../forms/chat-title-form';

export default function ChatScreen({
    t,
    assistant,
    assistantId,
    chats,
    messages,
}: {
    t: (key: string) => string;
    assistant: ChatAssistant;
    assistantId: string;
    chats: Chat[];
    messages: ChatMessage[];
}) {
    // initial chat id
    const initialChatId = (route().params.chat as string) ?? null;

    // chat state hook
    const chatState = usePersonalizedChatScreen({
        assistantId,
        chats,
        initialChatId,
        initialMessages: messages,
    });

    return (
        <ChatLayout>
            {/* Sidebar */}
            <ChatSidebar
                t={t}
                chats={chats}
                visibleChats={chatState.filteredChats}
                activeChatId={chatState.chatId}
                search={chatState.search}
                onSearch={chatState.setSearch}
                chatHref={(chatId) =>
                    route('dashboard.user.personalized-chat.chat', {
                        assistant: assistantId,
                        chat: chatId,
                    })
                }
                newChatHref={route('dashboard.user.personalized-chat.chat', {
                    assistant: assistantId,
                })}
                onDeleteChat={(chatId, setDeleting) => {
                    deleteResource({
                        url: route('dashboard.user.personalized-chat.destroy', {
                            assistant: assistantId,
                            id: chatId,
                        }),
                        t,
                        onStart: () => setDeleting(true),
                        onFinish: () => setDeleting(false),
                    });
                }}
                renderEditForm={(chat) => (
                    <ChatTitleForm
                        t={t}
                        chat={chat ?? null}
                        url="dashboard.user.personalized-chat.update"
                    />
                )}
            />

            {/* Messages */}
            <ChatMessages
                t={t}
                typingLabel={
                    <>
                        <span className="font-medium">
                            {t(assistant.chat_assistant_name)}
                        </span>{' '}
                        {t('is typing...')}
                    </>
                }
                emptyState={
                    <div className="flex flex-1 items-center justify-center">
                        <div className="max-w-md text-center">
                            <h2 className="text-2xl font-semibold tracking-tight">
                                {t('Hello there!')}
                            </h2>

                            <p className="mt-2 text-sm text-muted-foreground">
                                {t(
                                    'How can I help you today? Ask me anything to start this conversation',
                                )}
                            </p>
                        </div>
                    </div>
                }
                messages={chatState.conversation}
                input={chatState.input}
                sending={chatState.sending}
                onInputChange={chatState.setInput}
                onSend={chatState.sendMessage}
            />
        </ChatLayout>
    );
}
