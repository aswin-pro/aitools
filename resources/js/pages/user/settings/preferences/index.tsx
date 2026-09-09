import HeadingSmall from '@/components/heading-small';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/app/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { SharedData, type BreadcrumbItem } from '@/types';
import { LanguageList } from '@/types/user';
import { Head, router, usePage } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';

export default function Index({ languages }: { languages: LanguageList[] }) {
    // breadcrumbs
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: route('dashboard.user.overview'),
        },
        {
            title: 'Settings',
            href: route('dashboard.user.settings'),
        },
        {
            title: 'Preferences',
            href: '#',
        },
    ];

    // translation
    const { t } = useTranslation();

    // props
    const { auth } = usePage<SharedData>().props;

    // update language
    const updateLanguage = (newLang: string) => {
        router.put(
            route('dashboard.user.settings.preferences'),
            {
                language: newLang,
            },
            {
                preserveScroll: true,
                preserveState: false,
                onError: (errors) => {
                    Object.values(errors).forEach((msg) => toast.error(t(msg)));
                },
                onSuccess: () => {
                    window.location.reload();
                },
            },
        );
    };

    return (
        // App Layout
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* meta title */}
            <Head title="Preferences Settings" />

            {/* Settings Layout */}
            <SettingsLayout>
                <div className="space-y-6">
                    {/* Heading */}
                    <HeadingSmall
                        t={t}
                        title="Preferences"
                        description="Update your preferences"
                    />

                    <div className="grid grid-cols-1 gap-5">
                        <div className="space-y-4">
                            <Label htmlFor="language">{t('Language')}</Label>
                            <Select
                                value={auth.user.lang}
                                onValueChange={updateLanguage}
                            >
                                <SelectTrigger className="w-full">
                                    <SelectValue placeholder={t('Select')} />
                                </SelectTrigger>

                                <SelectContent>
                                    {languages.map((l) => (
                                        <SelectItem
                                            key={l.lang_key}
                                            value={l.lang_key}
                                        >
                                            {l.lang_name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
