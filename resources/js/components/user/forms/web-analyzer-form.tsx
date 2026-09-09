import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { LoadingSwap } from '@/components/ui/loading-swap';
import { showDynamicToast } from '@/helpers/show-dynamic-toast';
import { Form } from '@inertiajs/react';

export default function WebAnalyzerForm({ t }: { t: (key: string) => string }) {
    return (
        <Form
            method="post"
            action={route('dashboard.user.site-analyzer.analyze')}
            options={{ preserveScroll: true, preserveState: false }}
            className="mt-6 flex w-full gap-2"
            onSuccess={(page) => {
                const flash = page.props.flash as {
                    error?: string;
                };

                if (flash.error) {
                    showDynamicToast(flash.error);
                }
            }}
        >
            {({ processing }) => (
                <>
                    <div className="w-full">
                        <Label htmlFor="url" className="sr-only">
                            {t('URL')}
                        </Label>
                        <Input
                            id="url"
                            name="url"
                            type="url"
                            placeholder={t('example.com')}
                            className="w-full"
                            required
                        />
                    </div>

                    {/* Submit */}
                    <Button disabled={processing}>
                        <LoadingSwap isLoading={processing}>
                            {t('Analyze')}
                        </LoadingSwap>
                    </Button>
                </>
            )}
        </Form>
    );
}
