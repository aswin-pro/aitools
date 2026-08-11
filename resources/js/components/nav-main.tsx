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
import { ChevronRight, LayoutGrid, Settings } from "lucide-react";
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
                        url: "admin.dashboard",
                        icon: LayoutGrid,
                        isActive: route().current("admin.dashboard"),
                    },
                    {
                        title: "Settings",
                        url: "admin.edit.account",
                        icon: Settings,
                        isActive: route().current("admin.index.*"),
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
                                                    {item
                                                        .children!.filter(
                                                            (child) =>
                                                                child.condition,
                                                        )
                                                        .map((child) => (
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
                                                        ))}
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
