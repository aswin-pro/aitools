import { Badge } from '@/components/ui/badge';
import { deleteResource } from '@/helpers/delete-resource';
import { useSiteAnalyzerChatScreen } from '@/hooks/use-site-analyzer-chat-screen';
import { Chat, ChatMessage } from '@/types/user';
import { ChatLayout } from '../chat/chat-layout';
import { ChatMessages } from '../chat/chat-messages';
import { ChatSidebar } from '../chat/chat-side-bar';
import ChatTitleForm from '../forms/chat-title-form';
import { WelcomeMessage } from './welcome-message';

export default function ChatScreen({
    t,
    chats,
    messages,
}: {
    t: (key: string) => string;
    chats: Chat[];
    messages: ChatMessage[];
}) {
    // initial chat id
    const initialChatId = (route().params.chat as string) ?? null;

    // chat state hook
    const chatState = useSiteAnalyzerChatScreen({
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
                    route('dashboard.user.site-analyzer.index', {
                        chat: chatId,
                    })
                }
                newChatHref={route('dashboard.user.site-analyzer.index')}
                onDeleteChat={(chatId, setDeleting) => {
                    deleteResource({
                        url: route('dashboard.user.site-analyzer.destroy', {
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
                        url="dashboard.user.site-analyzer.update"
                    />
                )}
            />

            {/* Messages */}
            <ChatMessages
                t={t}
                typingLabel={<>{t('Analyzing...')}</>}
                emptyState={<WelcomeMessage t={t} />}
                messages={chatState.conversation}
                input={chatState.input}
                sending={chatState.sending}
                onInputChange={chatState.setInput}
                onSend={chatState.sendMessage}
                composerExtra={
                    initialChatId && (
                        <Badge className="bg-green-100 border-green-200 text-green-600 dark:bg-green-950/30 dark:border-green-800 dark:text-green-400">
                            <p className="max-w-40 truncate p-0.5">
                                {
                                    chats.find(
                                        (chat) =>
                                            chat.chat_id === initialChatId,
                                    )?.attachment
                                }
                            </p>
                        </Badge>
                    )
                }
            />
        </ChatLayout>
    );
}
