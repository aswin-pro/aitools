import { type NavItem } from "@/types";
import { Bot, Cloud, Globe } from "lucide-react";

export const adminSettingsNav: NavItem[] = [
    {
        title: "Profile",
        url: route("admin.edit.account"),
    },
    {
        title: "Password",
        url: route("admin.change.password"),
    },
    {
        title: "System Settings",
        url: route("admin.settings"),
    },
    {
        title: "Website Configuration",
        url: route("admin.dashboard"),
    },
    {
        title: "AI Tools Configuration",
        url: route("admin.dashboard"),
    },
    {
        title: "AWS S3 Configuration",
        url: route("admin.dashboard"),
    },
];

