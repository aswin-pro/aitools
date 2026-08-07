import { type NavItem } from "@/types";

export const adminSettingsNav: NavItem[] = [
    {
        title: "Profile",
        url: route("admin.edit.account"),
        icon: null,
    },
    {
        title: "Password",
        url: route("admin.change.password"),
        icon: null,
    },
];

