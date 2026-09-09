import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Dropzone } from '@/components/ui/dropzone';
import { LoadingSwap } from '@/components/ui/loading-swap';
import { showDynamicToast } from '@/helpers/show-dynamic-toast';
import { Form } from '@inertiajs/react';
import { useState } from 'react';
import { toast } from 'sonner';

export default function SpeechToTextConversionForm({
    t,
}: {
    t: (key: string) => string;
}) {
    // states
    const [file, setFile] = useState<File | null>(null);

    return (
        <div>
            <Card>
                <CardContent>
                    <Form
                        method="post"
                        action={route(
                            'dashboard.user.speech-to-text.convert.store',
                        )}
                        options={{ preserveScroll: true }}
                        className="space-y-5"
                        onSuccess={(page) => {
                            const flash = page.props.flash as {
                                error?: string;
                            };

                            if (flash.error) {
                                showDynamicToast(flash.error);
                            }
                        }}
                        onError={(errors) => {
                            Object.values(errors).forEach((error) => {
                                toast.error(t(error));
                            });
                        }}
                    >
                        {({ processing }) => (
                            <>
                                <div className="grid grid-cols-1 items-start gap-5">
                                    {/* dropzone */}
                                    <Dropzone
                                        t={t}
                                        name="file"
                                        file={file}
                                        onChange={setFile}
                                        label="Upload Audio File"
                                        accept={{
                                            'audio/*': [
                                                '.mp3',
                                                '.mpeg',
                                                '.mpga',
                                                '.m4a',
                                                '.wav',
                                                '.webm',
                                            ],
                                        }}
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
        </div>
    );
}
