import {
    SidebarGroup,
    SidebarGroupContent,
    SidebarInput,
} from '@/components/ui/sidebar';
import { Search } from 'lucide-react';

export function ChatSearchBar({
    t,
    value,
    onChange,
}: {
    t: (key: string) => string;
    value: string;
    onChange: (value: string) => void;
}) {
    return (
        <SidebarGroup className="px-3.5 py-4">
            <SidebarGroupContent className="relative">
                <SidebarInput
                    value={value}
                    onChange={(e) => onChange(e.target.value)}
                    placeholder={t('Search chats...')}
                    className="pl-8"
                />

                <Search className="pointer-events-none absolute top-1/2 left-2 size-4 -translate-y-1/2 opacity-50" />
            </SidebarGroupContent>
        </SidebarGroup>
    );
}
