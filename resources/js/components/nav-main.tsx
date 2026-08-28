import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from "@/components/ui/collapsible";
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from "@/components/ui/sidebar";
import { SharedData } from "@/types";
import { Link, usePage } from "@inertiajs/react";
import {
    ArrowRightLeft,
    ChevronRight,
    CircleDollarSign,
    FilePenLine,
    FolderCog,
    LayoutGrid,
    MessagesSquare,
    Settings,
    UsersRound,
} from "lucide-react";
import { useState } from "react";
import { useTranslation } from "react-i18next";

export function NavMain() {
    const { t } = useTranslation();

    const [openMenu, setOpenMenu] = useState<string | null>(null);
    const role = usePage<SharedData>().props.role;
    let navItems = [];

    if (role == 1) {
        navItems = [
            {
                title: t("Dashboard"),
                items: [
                    {
                        title: "Overview",
                        url: "dashboard.admin.overview",
                        icon: LayoutGrid,
                        isActive: route().current("dashboard.admin.overview"),
                    },
                    {
                        title: "Chat Assistants",
                        url: "dashboard.admin.chatgenius",
                        icon: MessagesSquare,
                        isActive: route().current("dashboard.admin.chatgenius"),
                    },
                    //users 
                    {
                        title: "Users",
                        url: "dashboard.admin.users",
                        icon: UsersRound,
                        isActive: route().current("dashboard.admin.users"),
                    },
                    //blogs 
                    {
                        title: "Blogs",
                        icon: FilePenLine,
                        isActive: route().current("dashboard.admin.blog.*"),
                        children: [
                            {
                                title: "Blog Posts",
                                url: "dashboard.admin.blogs.post",
                                isActive: route().current( "dashboard.admin.blogs.post"),
                            },
                            {
                                title: "Categories",
                                url: "dashboard.admin.blog.categories",
                                isActive: route().current(
                                    "dashboard.admin.blog.categories",
                                ),
                            },
                        ],
                    },
                    
                    {
                        title: "Transactions",
                        url: "dashboard.admin.transactions",
                        icon: ArrowRightLeft,
                        isActive: route().current(
                            "dashboard.admin.transactions",
                        ),
                    },

                    {
                        title: "Currencies",
                        url: "dashboard.admin.currencies",
                        icon: CircleDollarSign,
                        isActive: route().current("dashboard.admin.currencies"),
                    },

                    //system
                    {
                        title: "System",
                        icon: FolderCog,
                        isActive: route().current("dashboard.admin.system.*"),
                        children: [
                            {
                                title: "Login Activity",
                                url: "dashboard.admin.system.login-activity",
                                isActive: route().current(
                                    "dashboard.admin.system.login-activity",
                                ),
                            },
                            {
                                title: "Clear Cache",
                                url: "dashboard.admin.system.clear-cache",
                                isActive: route().current(
                                    "dashboard.admin.system.clear-cache",
                                ),
                            },
                            {
                                title: "Sitemap",
                                url: "dashboard.admin.system.sitemap",
                                isActive: route().current(
                                    "dashboard.admin.system.sitemap",
                                ),
                            },
                        ],
                    },

                    {
                        title: "Settings",
                        url: "dashboard.admin.edit.account",
                        icon: Settings,
                        isActive: route().current("dashboard.admin.edit.*"),
                    },
                ],
            },
        ];
    } else {
        navItems = [
            {
                title: t("Dashboard"),
                items: [
                    {
                        title: "Dashboard",
                        url: "user.dashboard",
                        icon: LayoutGrid,
                        isActive: route().current("user.dashboard"),
                    },
                    {
                        title: "Settings",
                        url: "user.settings",
                        icon: Settings,
                        isActive: route().current("user.settings.*"),
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
                            if ("children" in item) {
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
                                                    <ChevronRight className="ml-auto transition-transform group-data-[state=open]/collapsible:rotate-90" />
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
                                            <item.icon />
                                            <span>{t(item.title)}</span>
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
