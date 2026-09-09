import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { Chat } from '@/types/user';
import { Link } from '@inertiajs/react';
import { Ellipsis, SquarePen, Trash2 } from 'lucide-react';

export function ChatList({
    t,
    chats,
    activeChatId,
    chatHref,
    onNavigate,
    onEdit,
    onDelete,
}: {
    t: (key: string) => string;
    chats: Chat[];
    activeChatId: string | null;
    chatHref: (chatId: string) => string;
    onNavigate?: () => void;
    onEdit: (chatId: string) => void;
    onDelete: (chatId: string) => void;
}) {
    // empty msg
    if (chats.length === 0) {
        return (
            <p className="p-4 text-center text-sm text-muted-foreground">
                {t('No chats found')}
            </p>
        );
    }

    return (
        <nav className="flex flex-col gap-1 p-2.5">
            {/* label */}
            <h3 className="mb-1 text-sm font-medium text-muted-foreground">
                {t('Recents')}
            </h3>

            {/* render chats */}
            {chats.map((item) => (
                <SidebarMenuItem key={item.chat_id}>
                    <SidebarMenuButton
                        asChild
                        isActive={item.chat_id === activeChatId}
                    >
                        <Link
                            href={chatHref(item.chat_id)}
                            onClick={onNavigate}
                            className="flex items-center justify-between rounded-md px-2 py-2 hover:bg-muted/50"
                        >
                            <span className="max-w-[calc(100vw-180px)] truncate md:max-w-64 lg:max-w-48">
                                {t(item.chat_title)}
                            </span>

                            <DropdownMenu>
                                <DropdownMenuTrigger asChild>
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        size="icon"
                                        className="-me-1.5 h-8 w-8"
                                        onClick={(e) => {
                                            e.preventDefault();
                                            e.stopPropagation();
                                        }}
                                    >
                                        <Ellipsis className="h-4 w-4" />
                                    </Button>
                                </DropdownMenuTrigger>

                                <DropdownMenuContent align="end">
                                    {/* Edit */}
                                    <DropdownMenuItem
                                        onClick={(e) => {
                                            e.preventDefault();
                                            e.stopPropagation();
                                            onEdit(item.chat_id);
                                        }}
                                    >
                                        <SquarePen className="h-4 w-4" />
                                        {t('Edit')}
                                    </DropdownMenuItem>

                                    {/* Seperator */}
                                    <DropdownMenuSeparator />

                                    {/* Delete */}
                                    <DropdownMenuItem
                                        className="text-destructive data-highlighted:bg-destructive/10 data-highlighted:text-destructive"
                                        onClick={(e) => {
                                            e.preventDefault();
                                            e.stopPropagation();
                                            onDelete(item.chat_id);
                                        }}
                                    >
                                        <Trash2 className="h-4 w-4 text-destructive" />
                                        {t('Delete')}
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            ))}
        </nav>
    );
}
