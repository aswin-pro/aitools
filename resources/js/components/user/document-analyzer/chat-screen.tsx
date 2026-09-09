import { CustomBadge } from '@/components/custom-badge';
import { InputGroupButton } from '@/components/ui/input-group';
import { deleteResource } from '@/helpers/delete-resource';
import { useDocumentAnalyzerChatScreen } from '@/hooks/use-document-analyzer-chat-screen';
import { Chat, ChatMessage } from '@/types/user';
import { Plus } from 'lucide-react';
import { useState } from 'react';
import { ChatLayout } from '../chat/chat-layout';
import { ChatMessages } from '../chat/chat-messages';
import { ChatSidebar } from '../chat/chat-side-bar';
import DocumentSelector from '../common/document-selector';
import ChatTitleForm from '../forms/chat-title-form';

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

    // states
    const [dialogOpen, setDialogOpen] = useState<boolean>(false);

    // chat state hook
    const chatState = useDocumentAnalyzerChatScreen({
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
                    route('dashboard.user.document-analyzer.index', {
                        chat: chatId,
                    })
                }
                newChatHref={route('dashboard.user.document-analyzer.index')}
                onDeleteChat={(chatId, setDeleting) => {
                    deleteResource({
                        url: route('dashboard.user.document-analyzer.destroy', {
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
                        url="dashboard.user.document-analyzer.update"
                    />
                )}
            />

            {/* Messages */}
            <ChatMessages
                t={t}
                typingLabel={
                    <>
                        <span className="font-medium">
                            {t('Analyzing...')}
                        </span>{' '}
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
                                    'How can I help you today? Select a file and ask me anything to get started.',
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
                composerExtra={
                    <>
                        <div className="relative">
                            {/* File upload */}
                            <InputGroupButton
                                type="button"
                                variant="outline"
                                size="icon-sm"
                                className="rounded-full"
                                onClick={() => setDialogOpen(true)}
                            >
                                <Plus className="h-4 w-4" />
                            </InputGroupButton>

                            <CustomBadge
                                type="info"
                                className="absolute -top-1 -right-1 z-10 flex h-4 w-4 items-center justify-center rounded-full bg-primary p-1 text-xs! text-white"
                            >
                                {chatState.file ? 1 : 0}
                            </CustomBadge>
                        </div>

                        {/* document selector */}
                        <DocumentSelector
                            t={t}
                            dialogOpen={dialogOpen}
                            setDialogOpen={setDialogOpen}
                            file={chatState.file}
                            onFileChange={chatState.setFile}
                        />
                    </>
                }
            />
        </ChatLayout>
    );
}
