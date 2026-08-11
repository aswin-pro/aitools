import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { cn } from '@/lib/utils';
import { SharedData, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { UserKey, UserPen } from 'lucide-react';
import { type PropsWithChildren } from 'react';
import { useTranslation } from 'react-i18next';

export default function SettingsLayout({ children }: PropsWithChildren) {
    // i18n
    const { t } = useTranslation();
    
    const role = usePage<SharedData>().props.role;

    let sidebarNavItems: NavItem[] = [];

    if (role === 1) {
        sidebarNavItems = [
            {
                title: 'Profile',
                url: route('dashboard.admin.edit.account'),
                route: 'dashboard.admin.edit.account',
                icon: null,
            },
            {
                title: 'Password',
                url: route('dashboard.admin.change.password'),
                route: 'dashboard.admin.change.password',
                icon: null,
            },
            {
                title: 'System Settings',
                url: route('dashboard.admin.settings'),
                route: 'dashboard.admin.settings',
                icon: null,
            },
        ];
    } else {
        sidebarNavItems = [
            {
                title: "Profile",
                url: route("dashboard.user.settings.profile"),
                route: "dashboard.user.settings.profile",
                icon: UserPen,
            },
            {
                title: "Password",
                url: route("dashboard.user.settings.password"),
                route: "dashboard.user.settings.password",
                icon: UserKey,
            },
        ];
    }

    if (typeof window === "undefined") {
        return null;
    }

    // current path
    const currentPath = window.location.pathname;    


  return (
        <div>
            <Heading
                title="Settings"
                description="Manage your profile and account settings"
            />

            <div className="flex flex-col lg:flex-row lg:space-x-10">
                <aside className="w-full max-w-xl lg:w-60 border rounded-lg p-3">
                    <nav className="flex flex-col space-y-1 space-x-0">
                        {sidebarNavItems.map((item, index) => (
                            <Button
                                key={`${item.url}-${index}`}
                                size="sm"
                                variant="ghost"
                                asChild
                                className={cn("w-full justify-start", {
                                    "bg-muted": currentPath === item.url,
                                })}
                            >
                                <Link href={item.url}>
                                    {item.icon && (
                                        <item.icon className="h-4 w-4" />
                                    )}
                                    {t(item.title)}
                                </Link>
                            </Button>
                        ))}
                    </nav>
                </aside>

                <Separator className="my-6 lg:hidden" />

                <div className="flex-1 md:max-w-2xl">
                    <section className="max-w-xl space-y-12">
                        {children}
                    </section>
                </div>
            </div>
        </div>
    );
}
