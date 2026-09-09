import DynamicRenderFields from '@/components/form/dynamic-render-fields';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { LoadingSwap } from '@/components/ui/loading-swap';
import { Skeleton } from '@/components/ui/skeleton';
import { assetUrl } from '@/helpers/asset-url';
import { showDynamicToast } from '@/helpers/show-dynamic-toast';
import { FieldType } from '@/types';
import { Form } from '@inertiajs/react';
import { Download } from 'lucide-react';
import { Fragment, useState } from 'react';

export default function ImageGeneratorForm({
    t,
    response,
}: {
    t: (key: string) => string;
    response: Record<string, string>;
}) {
    // states
    const [loading, setLoading] = useState<boolean>(false);

    // fields
    const fields: FieldType[] = [
        {
            id: 'prompt',
            label: 'Prompt',
            fieldType: 'text-area',
            props: {
                placeholder: t('Ex: A beautiful landscape'),
                className: 'min-h-36',
                minLength: 3,
                maxLength: 1000,
                required: true,
            },
        },
        {
            id: 'size',
            label: 'Size',
            fieldType: 'select',
            props: {
                defaultValue: '1:1',
                placeholder: t('Select'),
                options: [
                    { label: 'Square (1:1)', value: '1:1' },
                    { label: 'Portrait (9:16)', value: '9:16' },
                    { label: 'Landscape (16:9)', value: '16:9' },
                ],
                required: true,
            },
        },
        {
            id: 'style',
            label: 'Style',
            fieldType: 'select',
            props: {
                defaultValue: 'Photo',
                placeholder: t('Select'),
                options: [
                    {
                        label: 'Photography',
                        items: [
                            { label: 'Photo', value: 'Photo' },
                            { label: 'Vibrant', value: 'Vibrant' },
                            { label: 'Minimalist', value: 'Minimalist' },
                            { label: 'Neon', value: 'Neon' },
                            { label: 'Filmic', value: 'Filmic' },
                            { label: 'Realistic', value: 'Realistic' },
                            { label: 'Modern', value: 'Modern' },
                        ],
                    },
                    {
                        label: 'Digital art',
                        items: [
                            { label: 'Colorful', value: 'Colorful' },
                            { label: '3D', value: '3D' },
                            { label: 'Retrowave', value: 'Retrowave' },
                            { label: 'Psychedelic', value: 'Psychedelic' },
                            { label: '3D Model', value: '3D Model' },
                            { label: 'Concept art', value: 'Concept art' },
                            { label: 'Gradient', value: 'Gradient' },
                            { label: 'Retro Anime', value: 'Retro Anime' },
                            { label: 'Dreamlike', value: 'Dreamlike' },
                            { label: 'Anime', value: 'Anime' },
                            { label: 'Sticker', value: 'Sticker' },
                            { label: 'Vector', value: 'Vector' },
                            { label: 'Cartoon', value: 'Cartoon' },
                            { label: 'Avatar', value: 'Avatar' },
                        ],
                    },
                    {
                        label: 'Fine art',
                        items: [
                            { label: 'Watercolor', value: 'Watercolor' },
                            { label: 'Color pencil', value: 'Color pencil' },
                            { label: 'Stained Glass', value: 'Stained Glass' },
                            { label: 'Ink Print', value: 'Ink Print' },
                            { label: 'Line Art', value: 'Line Art' },
                        ],
                    },
                    {
                        label: 'Others',
                        items: [
                            { label: 'Emoji', value: 'Emoji' },
                            { label: 'Meme', value: 'Meme' },
                        ],
                    },
                ],
                required: true,
            },
        },
    ];

    // Download image
    const downloadImage = async (imagePath: string, imageName: string) => {
        // image url
        const imageUrl = assetUrl(imagePath);

        // fetch image
        const response = await fetch(imageUrl);
        // make as blob
        const blob = await response.blob();

        // create url
        const url = window.URL.createObjectURL(blob);

        // create link
        const link = document.createElement('a');

        // set link
        link.href = url;
        link.download = `${imageName || 'generated-image'}.png`;

        // append link
        document.body.appendChild(link);
        // click link
        link.click();
        // remove link
        link.remove();

        // revoke url
        window.URL.revokeObjectURL(url);
    };

    return (
        <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
            {/* Form */}
            <Card>
                <CardContent>
                    <Form
                        method="post"
                        action={route(
                            'dashboard.user.image-generator.generate.store',
                        )}
                        options={{ preserveScroll: true }}
                        className="space-y-5"
                        onStart={() => setLoading(true)}
                        onSuccess={(page) => {
                            const flash = page.props.flash as {
                                error?: string;
                            };

                            if (flash.error) {
                                showDynamicToast(flash.error);
                            }
                        }}
                        onFinish={() => setLoading(false)}
                    >
                        {({ processing, errors }) => (
                            <>
                                <div className="grid grid-cols-1 items-start gap-5">
                                    <DynamicRenderFields
                                        t={t}
                                        fields={fields}
                                        errors={errors}
                                    />
                                </div>

                                {/* Submit */}
                                <Button disabled={processing}>
                                    <LoadingSwap isLoading={processing}>
                                        {t('Generate')}
                                    </LoadingSwap>
                                </Button>
                            </>
                        )}
                    </Form>
                </CardContent>
            </Card>

            {/* Result */}
            <Card className="flex-1">
                <CardContent className="relative flex h-full items-center justify-center">
                    {/* Image Preview */}
                    {loading ? (
                        <Skeleton className="h-full min-h-80 w-full" />
                    ) : response?.image ? (
                        <Fragment>
                            <Button
                                variant="secondary"
                                size="icon"
                                className="absolute inset-e-8 top-3 rounded-full"
                                onClick={() =>
                                    downloadImage(response.image, response.name)
                                }
                            >
                                <Download className="h-4 w-4" />
                            </Button>
                            <img
                                src={assetUrl(response.image)}
                                alt="Image Preview"
                                className="h-full w-full rounded-lg object-cover"
                            />
                        </Fragment>
                    ) : (
                        <div className="text-sm text-muted-foreground">
                            {t('Generated image will appear here.')}
                        </div>
                    )}
                </CardContent>
            </Card>

            {/* Precautions */}
            <div className="text-center">
                <p className="text-xs text-muted-foreground">
                    {t(
                        'AI can make mistakes. Please review the image before using it.',
                    )}
                </p>
            </div>
        </div>
    );
}
