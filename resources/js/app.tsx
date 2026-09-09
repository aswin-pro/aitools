import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ElementType, StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import { Toaster } from 'sonner';
import '../css/theme.css';
import '../css/app.css';
import './hooks/i18n';
import { initializeTheme, useAppearance } from './hooks/use-appearance';
import { SharedData } from './types';
import i18n from './hooks/i18n';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

function Root<T extends object>({
    App,
    props,
}: {
    App: ElementType;
    props: T;
}) {
    const { isDark } = useAppearance();

    return (
        <>
            <App {...props} />
            <Toaster
                position="bottom-right"
                richColors
                theme={isDark ? 'dark' : 'light'}
            />
        </>
    );
}

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.tsx`,
            import.meta.glob('./pages/**/*.tsx'),
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        const pageProps = props.initialPage.props as unknown as SharedData;

        const lang = pageProps.auth.user?.lang ?? 'en';
        const rtlLanguages = ['ar', 'ur', 'he', 'fa'];

        i18n.changeLanguage(lang);

        // Set HTML language and direction
        document.documentElement.lang = lang;
        document.documentElement.dir = rtlLanguages.includes(lang)
            ? 'rtl'
            : 'ltr';

        root.render(
            <StrictMode>
                <Root App={App} props={props} />
            </StrictMode>,
        );
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on load...
initializeTheme();
