import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import {
    ArrowRightLeft,
    Blocks,
    Bot,
    BotMessageSquare,
    CalendarClock,
    ChevronRight,
    CircleDollarSign,
    CodeXml,
    FileBox,
    FilePenLine,
    FileText,
    FolderCog,
    Form,
    Globe,
    Image,
    LayoutGrid,
    MessageSquareText,
    MessagesSquare,
    PencilSparkles,
    Settings,
    StickyNote,
    UsersRound,
    WalletCards,
} from 'lucide-react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';

export function NavMain() {
    // translation
    const { t } = useTranslation();

    // role
    const role = usePage<SharedData>().props.role;

    // states
    const [openMenu, setOpenMenu] = useState<string | null>(null);

    // nav items
    let navItems = [];

    if (role == 1) {
        navItems = [
            {
                title: t('Dashboard'),
                items: [
                    {
                        title: 'Overview',
                        url: 'dashboard.admin.overview',
                        icon: LayoutGrid,
                        isActive: route().current('dashboard.admin.overview'),
                    },
                    //chat Assistants
                    {
                        title: 'Content Templates',
                        icon: Form,
                        isActive: route().current(
                            'dashboard.admin.content-templates.*',
                        ),
                        children: [
                            {
                                title: 'Templates',
                                url: 'dashboard.admin.templates',
                                isActive: route().current(
                                    'dashboard.admin.templates',
                                ),
                            },
                            {
                                title: 'Categories',
                                url: 'dashboard.admin.categories',
                                isActive: route().current(
                                    'dashboard.admin.categories',
                                ),
                            },
                        ],
                    },
                    //chat Assistants
                    {
                        title: 'Chat Assistants',
                        url: 'dashboard.admin.chatgenius',
                        icon: MessagesSquare,
                        isActive: route().current('dashboard.admin.chatgenius'),
                    },
                    //users
                    {
                        title: 'Users',
                        url: 'dashboard.admin.users',
                        icon: UsersRound,
                        isActive: route().current('dashboard.admin.users'),
                    },
                    //plans
                    {
                        title: 'Plans',
                        url: 'dashboard.admin.index.plans',
                        icon: FileBox,
                        isActive: route().current(
                            'dashboard.admin.index.plans',
                        ),
                    },
                    //payment methods
                    {
                        title: 'Payment Methods',
                        url: 'dashboard.admin.payment.methods',
                        icon: WalletCards,
                        isActive: route().current(
                            'dashboard.admin.payment.methods',
                        ),
                    },
                    //blogs
                    {
                        title: 'Blogs',
                        icon: FilePenLine,
                        isActive: route().current('dashboard.admin.blog.*'),
                        children: [
                            {
                                title: 'Blog Posts',
                                url: 'dashboard.admin.blogs.post',
                                isActive: route().current(
                                    'dashboard.admin.blogs.post',
                                ),
                            },
                            {
                                title: 'Categories',
                                url: 'dashboard.admin.blog.categories',
                                isActive: route().current(
                                    'dashboard.admin.blog.categories',
                                ),
                            },
                        ],
                    },

                    {
                        title: 'Pages',
                        url: 'dashboard.admin.pages',
                        icon: StickyNote,
                        isActive: route().current('dashboard.admin.pages'),
                    },

                    {
                        title: 'Transactions',
                        url: 'dashboard.admin.transactions',
                        icon: ArrowRightLeft,
                        isActive: route().current(
                            'dashboard.admin.transactions',
                        ),
                    },

                    {
                        title: 'Currencies',
                        url: 'dashboard.admin.currencies',
                        icon: CircleDollarSign,
                        isActive: route().current('dashboard.admin.currencies'),
                    },

                    //system
                    {
                        title: 'System',
                        icon: FolderCog,
                        isActive: route().current('dashboard.admin.system.*'),
                        children: [
                            {
                                title: 'Login Activity',
                                url: 'dashboard.admin.system.login-activity',
                                isActive: route().current(
                                    'dashboard.admin.system.login-activity',
                                ),
                            },
                            {
                                title: 'System Translations',
                                url: 'translation-manager.index',
                                isActive: route().current(
                                    'translation-manager.index',
                                ),
                            },

                            {
                                title: 'Sitemap',
                                url: 'dashboard.admin.system.sitemap',
                                isActive: route().current(
                                    'dashboard.admin.system.sitemap',
                                ),
                            },
                            {
                                title: 'Backups',
                                url: 'dashboard.admin.system.backups',
                                isActive: route().current(
                                    'dashboard.admin.system.backups',
                                ),
                            },
                            {
                                title: 'Clear Cache',
                                url: 'dashboard.admin.system.clear-cache',
                                isActive: route().current(
                                    'dashboard.admin.system.clear-cache',
                                ),
                            },
                        ],
                    },

                    {
                        title: 'Plugins',
                        url: 'dashboard.admin.plugins.index',
                        icon: Blocks,
                        isActive: route().current('dashboard.admin.edit.*'),
                    },

                    {
                        title: 'Settings',
                        url: 'dashboard.admin.edit.account',
                        icon: Settings,
                        isActive: route().current('dashboard.admin.edit.*'),
                    },
                ],
            },
        ];
    } else {
        navItems = [
            {
                title: t('Platform'),
                items: [
                    {
                        title: 'Overview',
                        url: 'dashboard.user.overview',
                        icon: LayoutGrid,
                        isActive: route().current('dashboard.user.overview'),
                    },
                    {
                        title: 'Content Generator',
                        url: 'dashboard.user.content-generator.index',
                        icon: PencilSparkles,
                        isActive: route().current(
                            'dashboard.user.content-generator.*',
                        ),
                    },
                    {
                        title: 'Image Generator',
                        url: 'dashboard.user.image-generator.index',
                        icon: Image,
                        isActive: route().current(
                            'dashboard.user.image-generator.*',
                        ),
                    },
                    {
                        title: 'Code Generator',
                        url: 'dashboard.user.code-generator.index',
                        icon: CodeXml,
                        isActive: route().current(
                            'dashboard.user.code-generator.*',
                        ),
                    },
                    {
                        title: 'Speech to Text',
                        url: 'dashboard.user.speech-to-text.index',
                        icon: BotMessageSquare,
                        isActive: route().current(
                            'dashboard.user.speech-to-text.*',
                        ),
                    },
                    {
                        title: 'Text to Speech',
                        url: 'dashboard.user.text-to-speech.index',
                        icon: Bot,
                        isActive: route().current(
                            'dashboard.user.text-to-speech.*',
                        ),
                    },
                    {
                        title: 'Personalized Chat',
                        url: 'dashboard.user.personalized-chat.index',
                        icon: MessageSquareText,
                        isActive: route().current(
                            'dashboard.user.personalized-chat.*',
                        ),
                    },
                    {
                        title: 'Document Analyzer',
                        url: 'dashboard.user.document-analyzer.index',
                        icon: FileText,
                        isActive: route().current(
                            'dashboard.user.document-analyzer.*',
                        ),
                    },
                    {
                        title: 'Site Analyzer',
                        url: 'dashboard.user.site-analyzer.index',
                        icon: Globe,
                        isActive: route().current(
                            'dashboard.user.site-analyzer.*',
                        ),
                    },
                    {
                        title: 'Subscriptions',
                        url: 'dashboard.user.subscriptions.index',
                        icon: CalendarClock,
                        isActive: route().current(
                            'dashboard.user.subscriptions.*',
                        ),
                    },
                    {
                        title: 'Settings',
                        url: 'dashboard.user.settings',
                        icon: Settings,
                        isActive: route().current('dashboard.user.settings.*'),
                    },
                ],
            },
        ];
    }

    return (
        <>
            {navItems.map((group) => (
                <SidebarGroup key={group.title} className="px-2 py-0">
                    <SidebarGroupLabel>{group.title}</SidebarGroupLabel>

                    <SidebarMenu className="-mb-1">
                        {group.items.map((item) => {
                            if ('children' in item) {
                                const active = item.children!.some(
                                    (child) => child.isActive,
                                );

                                return (
                                    <Collapsible
                                        key={item.title}
                                        defaultOpen={active}
                                        className="group/collapsible"
                                        open={openMenu === item.title}
                                        onOpenChange={(open) =>
                                            setOpenMenu(
                                                open ? item.title : null,
                                            )
                                        }
                                    >
                                        <SidebarMenuItem>
                                            <CollapsibleTrigger asChild>
                                                <SidebarMenuButton
                                                    isActive={active}
                                                >
                                                    <item.icon />
                                                    <span>{t(item.title)}</span>
                                                    <ChevronRight className="ms-auto transition-transform group-data-[state=open]/collapsible:rotate-90" />
                                                </SidebarMenuButton>
                                            </CollapsibleTrigger>

                                            <CollapsibleContent>
                                                <SidebarMenuSub>
                                                    {item.children!.map(
                                                        (child) => (
                                                            <SidebarMenuSubItem
                                                                key={
                                                                    child.title
                                                                }
                                                            >
                                                                <SidebarMenuSubButton
                                                                    asChild
                                                                    isActive={
                                                                        child.isActive
                                                                    }
                                                                >
                                                                    <Link
                                                                        href={route(
                                                                            child.url,
                                                                        )}
                                                                    >
                                                                        <span>
                                                                            {t(
                                                                                child.title,
                                                                            )}
                                                                        </span>
                                                                    </Link>
                                                                </SidebarMenuSubButton>
                                                            </SidebarMenuSubItem>
                                                        ),
                                                    )}
                                                </SidebarMenuSub>
                                            </CollapsibleContent>
                                        </SidebarMenuItem>
                                    </Collapsible>
                                );
                            }

                            return (
                                <SidebarMenuItem key={item.title}>
                                    <SidebarMenuButton
                                        asChild
                                        isActive={item.isActive}
                                        tooltip={item.title}
                                    >
                                        <Link href={route(item.url)}>
                                            <item.icon
                                                className={`${item.isActive ? 'text-primary dark:text-white' : ''}`}
                                            />
                                            <span
                                                className={`${item.isActive ? 'text-primary dark:text-white' : ''}`}
                                            >
                                                {t(item.title)}
                                            </span>
                                        </Link>
                                    </SidebarMenuButton>
                                </SidebarMenuItem>
                            );
                        })}
                    </SidebarMenu>
                </SidebarGroup>
            ))}
        </>
    );
}
