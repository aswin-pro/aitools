import { SharedData } from "@/types";
import AppLogoIcon from "./app-logo-icon";
import { usePage } from "@inertiajs/react";
import { assetUrl } from "@/helpers/asset-url";

export default function AppLogo() {
    const { settings } = usePage<SharedData>().props;

    return (
        <>
            <img
                src={assetUrl(settings?.site_logo_light)}
                alt="Logo"
                className="h-8 w-auto max-w-[140px] object-contain dark:hidden"
            />

            {/* Dark mode logo */}
            <img
                src={assetUrl(settings?.site_logo)}
                alt="Logo"
                className="hidden h-8 w-auto max-w-[140px] object-contain dark:block"
            />
        </>
    );
}
