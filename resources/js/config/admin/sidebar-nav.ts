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
        ]
    },

    {
        label: "Others",
        items: [
            {
                title: "Settings",
                url: route("admin.settings"),
                icon: Settings,
            },

        ],
    }

];

