import { type NavItem } from "@/types";
import { Bot, Cloud, Globe, Settings } from "lucide-react";

export const generalSettingNav: NavItem[] = [
    {
        title: "System Settings",
        url: route("admin.dashboard"),
        icon: Settings,
    },
    {
        title: "Website Configuration",
        url: route("admin.dashboard"),
        icon: Globe,
    },
    {
        title: "AI Tools Configuration",
        url: route("admin.dashboard"),
        icon: Bot,
    },
    {
        title: "AWS S3 Configuration",
        url: route("admin.dashboard"),
        icon: Cloud,
    },
];

