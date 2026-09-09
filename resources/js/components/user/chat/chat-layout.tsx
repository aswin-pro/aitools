import { Button } from '@/components/ui/button';
import { Sheet, SheetContent } from '@/components/ui/sheet';
import { Menu } from 'lucide-react';
import {
    Children,
    cloneElement,
    isValidElement,
    ReactNode,
    useState,
} from 'react';

export function ChatLayout({ children }: { children: ReactNode }) {
    // states
    const [mobileOpen, setMobileOpen] = useState(false);

    // sidebar content
    const [sidebar, content] = Children.toArray(children);

    return (
        <div className="flex h-[calc(100vh-195px)] overflow-hidden rounded-xl border">
            {/* Desktop sidebar */}
            <aside className="hidden w-64 shrink-0 border-e lg:flex">
                {sidebar}
            </aside>

            <div className="flex h-full min-h-0 flex-1 flex-col overflow-hidden">
                {/* Mobile toggle */}
                <div className="flex items-center border-b px-1 py-1 lg:hidden">
                    <Button
                        type="button"
                        variant="ghost"
                        size="icon"
                        onClick={() => setMobileOpen(true)}
                    >
                        <Menu className="h-5 w-5" />
                    </Button>
                </div>

                {/* Sidebar Content */}
                {content}
            </div>

            {/* Mobile drawer */}
            <Sheet open={mobileOpen} onOpenChange={setMobileOpen}>
                <SheetContent
                    side="left"
                    className="w-[calc(100vw-60px)] overflow-hidden p-0"
                >
                    {/* Sidebar Content */}
                    {isValidElement<{
                        mobile?: boolean;
                        onNavigate?: () => void;
                    }>(sidebar)
                        ? cloneElement(sidebar, {
                              mobile: true,
                              onNavigate: () => setMobileOpen(false),
                          })
                        : sidebar}
                </SheetContent>
            </Sheet>
        </div>
    );
}
