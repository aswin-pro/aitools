import {
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import { UserInfo } from '@/components/user-info';
import { useMobileNavigation } from '@/hooks/use-mobile-navigation';
import { SharedData } from '@/types';
import { User } from '@/types/user';
import { Link, router, usePage } from '@inertiajs/react';
import { LogOut, User as UserIcon } from 'lucide-react';
import { useTranslation } from 'react-i18next';

interface UserMenuContentProps {
    user: User;
}

export function UserMenuContent({ user }: UserMenuContentProps) {
    // mobile navigation
    const cleanup = useMobileNavigation();

    // handle logout
    const handleLogout = () => {
        cleanup();
        router.flushAll();
    };

    // translation
    const { t } = useTranslation();

    // role
    const role = usePage<SharedData>().props.role;

    // profile route
    const profileRoute =
        role === 1
            ? route('dashboard.admin.edit.account')
            : route('dashboard.user.settings.profile');

    return (
        <>
            <DropdownMenuLabel className="p-0 font-normal">
                <div className="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                    <UserInfo user={user} showEmail={true} />
                </div>
            </DropdownMenuLabel>
            <DropdownMenuSeparator />
            <DropdownMenuItem asChild>
                <Link className="block w-full" href={profileRoute} as="button">
                    <UserIcon />
                    {t('Profile')}
                </Link>
            </DropdownMenuItem>
            <DropdownMenuSeparator />

            <DropdownMenuItem asChild>
                <Link
                    className="block w-full text-red-600"
                    method="post"
                    href={route('logout')}
                    as="button"
                    onClick={handleLogout}
                    data-test="logout-button"
                >
                    <LogOut className="text-red-600" />
                    {t('Log out')}
                </Link>
            </DropdownMenuItem>
        </>
    );
}
