import { assetUrl } from '@/helpers/asset-url';
import { useAppearance } from '@/hooks/use-appearance';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import { useSidebar } from './ui/sidebar';

export default function AppLogo() {
    // check is dark
    const { isDark } = useAppearance();
    const { logo_dark, logo_light, favicon } = usePage<SharedData>().props;

    // Select logo based on theme
    const logo = isDark ? logo_dark : logo_light;

    // sidebar hook
    const sidebar = useSidebar();

    return (
        <>
            <div className="h-8">
                {sidebar.state === 'expanded' ? (
                    <img
                        src={assetUrl(logo)}
                        alt="Logo"
                        className="-ms-1 h-full"
                    />
                ) : (
                    <img
                        src={assetUrl(favicon)}
                        alt="Logo"
                        className="h-full"
                    />
                )}
            </div>
        </>
    );
}
