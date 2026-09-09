import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from '@/components/ui/sidebar';
import { UserInfo } from '@/components/user-info';
import { UserMenuContent } from '@/components/user-menu-content';
import { useIsMobile } from '@/hooks/use-mobile';
import { type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { ChevronsUpDown, Sparkles } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import { Button } from './ui/button';
import ProgressBar from './progress-bar';

export function NavUser() {
    // hooks
    const { auth, credits, role } = usePage<SharedData>().props;
    const { state } = useSidebar();
    const isMobile = useIsMobile();
    const { t } = useTranslation();

    const formatCredits = (value: number) =>
        new Intl.NumberFormat('en', {
            notation: 'compact',
            maximumFractionDigits: 1,
        }).format(value);

    return (
        <SidebarMenu>
            {/* AI Credits Progress */}
            {state === 'expanded' && role === 2 && (
                <SidebarMenuItem className="space-y-2 rounded-lg border p-2 text-sidebar-accent-foreground data-[state=open]:bg-sidebar-accent">
                    {/* progress bar */}
                    <ProgressBar />

                    {/* credits */}
                    <p className="text-[0.72rem]">
                        {formatCredits(
                            credits.ai_credits.total - credits.ai_credits.used,
                        )}{' '}
                        <span className="text-muted-foreground">
                            {t('Credits Remaining')}
                        </span>
                    </p>

                    {/* Subscriptions */}
                    <Link
                        href={route('dashboard.user.subscriptions.plans')}
                        className="w-full"
                    >
                        <Button size={'sm'} className="w-full" type="button">
                            <div className="flex shimmer items-center justify-center gap-1">
                                <Sparkles />
                                {t('Buy Credits')}
                            </div>
                        </Button>
                    </Link>
                </SidebarMenuItem>
            )}

            {/* User Info */}
            <SidebarMenuItem>
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <SidebarMenuButton
                            size="lg"
                            className="group border text-sidebar-accent-foreground data-[state=open]:bg-sidebar-accent"
                            data-test="sidebar-menu-button"
                        >
                            <UserInfo user={auth.user} />
                            <ChevronsUpDown className="ms-auto size-4" />
                        </SidebarMenuButton>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent
                        className="w-(--radix-dropdown-menu-trigger-width) min-w-56 rounded-lg"
                        align="end"
                        side={
                            isMobile
                                ? 'bottom'
                                : state === 'collapsed'
                                  ? 'left'
                                  : 'bottom'
                        }
                    >
                        <UserMenuContent user={auth.user} />
                    </DropdownMenuContent>
                </DropdownMenu>
            </SidebarMenuItem>
        </SidebarMenu>
    );
}
