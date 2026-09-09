import { ActionDialog } from '@/components/dialog/action-dialog';
import { BasicDialog } from '@/components/dialog/dialog';
import { Button } from '@/components/ui/button';
import { Chat } from '@/types/user';
import { Link } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import { ReactNode, useState } from 'react';
import { ChatList } from './chat-list';
import { ChatSearchBar } from './chat-search-bar';

export function ChatSidebar({
    t,
    chats,
    visibleChats,
    activeChatId,
    search,
    onSearch,
    chatHref,
    newChatHref,
    onDeleteChat,
    renderEditForm,
    mobile = false,
    onNavigate,
}: {
    t: (key: string) => string;
    chats: Chat[];
    visibleChats: Chat[];
    activeChatId: string | null;
    search: string;
    onSearch: (value: string) => void;
    chatHref: (chatId: string) => string;
    newChatHref: string;
    onDeleteChat: (
        chatId: string,
        setDeleting: (value: boolean) => void,
    ) => void;
    renderEditForm: (chat: Chat | undefined, close: () => void) => ReactNode;
    mobile?: boolean;
    onNavigate?: () => void;
}) {
    // states
    const [dialogOpen, setDialogOpen] = useState(false);
    const [dialogType, setDialogType] = useState<'edit' | 'delete'>('edit');
    const [selectedChatId, setSelectedChatId] = useState<string | null>(null);
    const [deleting, setDeleting] = useState(false);

    // Selected Chat
    const selectedChat = chats.find((chat) => chat.chat_id === selectedChatId);

    // open edit model
    const openEdit = (chatId: string) => {
        setSelectedChatId(chatId);
        setDialogType('edit');
        setDialogOpen(true);
    };

    // open delete model
    const openDelete = (chatId: string) => {
        setSelectedChatId(chatId);
        setDialogType('delete');
        setDialogOpen(true);
    };

    // perform delete
    const performDelete = () => {
        if (!selectedChatId) return;
        onDeleteChat(selectedChatId, setDeleting);
    };

    return (
        <div
            className={`flex h-full min-h-0 flex-col ${mobile ? 'pt-12' : ''}`}
        >
            {/* Search bar */}
            <div className="shrink-0 border-b">
                <ChatSearchBar t={t} value={search} onChange={onSearch} />
            </div>

            {/* Scrollable chats */}
            <div className="min-h-0 flex-1 overflow-y-auto">
                <ChatList
                    t={t}
                    chats={visibleChats}
                    activeChatId={activeChatId}
                    chatHref={chatHref}
                    onNavigate={onNavigate}
                    onEdit={openEdit}
                    onDelete={openDelete}
                />
            </div>

            {/* New chat button */}
            <div className="shrink-0 p-4">
                <Link href={newChatHref} onClick={onNavigate}>
                    <Button className="w-full">
                        <Plus />
                        {t('New Chat')}
                    </Button>
                </Link>
            </div>

            {/* Delete dialog */}
            <ActionDialog
                t={t}
                dialogOpen={dialogOpen && dialogType === 'delete'}
                setDialogOpen={setDialogOpen}
                title="Delete Chat"
                description="Are you sure you want to delete this chat?"
                handleAction={performDelete}
                loading={deleting}
            />

            {/* Edit dialog */}
            <BasicDialog
                t={t}
                dialogOpen={dialogOpen && dialogType === 'edit'}
                setDialogOpen={setDialogOpen}
                title="Edit Chat"
                description="Edit the chat title."
                size="sm:max-w-xl"
            >
                {renderEditForm(selectedChat, () => setDialogOpen(false))}
            </BasicDialog>
        </div>
    );
}
