import { LayoutGrid, PackageSearch, Settings, Globe, Bot, Cloud } from "lucide-react";
import { NavGroup } from "@/types";

export const adminSidebarNav: NavGroup[] = [

    {
        label: "Platform",
        items: [
            {
                title: "Dashboard",
                url: route("admin.dashboard"),
                icon: LayoutGrid,
            },
            {
                title: "Products",
                url: route("admin.dashboard"),
                icon: PackageSearch,
            },
        ]
    },

    {
        label: "Settings",
        items: [
            {
                title: "General Settings",
                url: route("admin.dashboard"),
                icon: Settings,
            },

        ],
    }

];

