import { LayoutGrid, PackageSearch } from "lucide-react";
import { NavItem } from "@/types";

export const adminSidebarNav: NavItem[] = [
    {
        title: "Dashboard",
        url: route("admin.dashboard"),
        icon: LayoutGrid,
    },
    {
        title: "Products",
        url: route("admin.products.index"),
        icon: PackageSearch,
    },
];