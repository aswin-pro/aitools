import { Button } from '@/components/ui/button';
import { FieldType } from '@/types';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import DynamicRenderFields from '../form/dynamic-render-fields';
import { LoadingSwap } from '../ui/loading-swap';

export default function ProductStockAlterForm({
    setDialogOpen,
    productId,
    measurementUnit,
}: {
    setDialogOpen: (open: boolean) => void;
    productId: string | null;
    measurementUnit: string | undefined;
}) {
    // auth
    const { t } = useTranslation();

    const fields: FieldType[] = [
        {
            id: 'stock',
            label: 'Stock',
            fieldType: 'input-group',
            props: {
                type: 'number',
                step: '0.001',
                placeholder: 'Ex: 10',
                defaultValue: 0,
                onKeyDown: (e: React.KeyboardEvent<HTMLInputElement>) => {
                    if (e.key === 'e' || e.key === 'E') {
                        e.preventDefault();
                    }
                },
                required: true,
            },
            inputGroup: {
                align: 'inline-end',
                content: measurementUnit,
            },
        },
    ];

    return (
        <Form
            method={'put'}
            action={route('dashboard.inventory.stock.alter', {
                product: productId,
            })}
            resetOnSuccess
            options={{ preserveScroll: true }}
            className="mt-2 space-y-5"
            onSuccess={() => {
                toast.success(t('Success!'));
                setDialogOpen(false);
            }}
        >
            {({ processing, errors }) => (
                <>
                    <div className="grid grid-cols-1 items-start gap-6">
                        <DynamicRenderFields fields={fields} errors={errors} />
                    </div>

                    {/* Submit */}
                    <Button disabled={processing}>
                        <LoadingSwap isLoading={processing}>
                            {t('Update')}
                        </LoadingSwap>
                    </Button>
                </>
            )}
        </Form>
    );
}
