import { type NavItem } from "@/types";

export const userSettingsNav: NavItem[] = [
    {
        title: "Profile",
        url: route("user.edit.account"),
        icon: null,
    },
    {
        title: "Password",
        url: route("user.change.password"),
        icon: null,
    },
];