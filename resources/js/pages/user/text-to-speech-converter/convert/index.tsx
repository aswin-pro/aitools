import { BasicDialog } from '@/components/dialog/dialog';
import Heading from '@/components/heading';
import { AudioPlayer } from '@/components/ui/audio-player';
import TextToSpeechConversionForm from '@/components/user/forms/text-to-speech-conversion-form';
import { assetUrl } from '@/helpers/asset-url';
import AppLayout from '@/layouts/app/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard.user.overview'),
    },
    {
        title: 'Text to Speech',
        href: route('dashboard.user.text-to-speech.index'),
    },
    {
        title: 'Convert',
        href: '#',
    },
];

export default function Index({
    response,
}: {
    response: Record<string, string>;
}) {
    // translation
    const { t } = useTranslation();

    // states
    const [dialogOpen, setDialogOpen] = useState(!!response?.generation_id);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            {/* Meta Data */}
            <Head title="Convert Text to Speech" />

            {/* heading */}
            <Heading
                t={t}
                title="Convert Text to Speech"
                description="Convert text into natural, high-quality speech with AI in multiple voices and languages."
            />

            <div className="grid grid-cols-1 gap-4">
                {/* Form */}
                <TextToSpeechConversionForm t={t} />

                {/* Dialog */}
                <BasicDialog
                    t={t}
                    key={response?.generation_id}
                    dialogOpen={dialogOpen}
                    setDialogOpen={setDialogOpen}
                    title="Preview"
                    description="Play the generated audio and review the output."
                    size="sm:max-w-xl"
                >
                    {/* audio player */}
                    <AudioPlayer
                        t={t}
                        name={response?.name ?? ''}
                        src={assetUrl(response?.conversion ?? '')}
                    />
                </BasicDialog>
            </div>
        </AppLayout>
    );
}
