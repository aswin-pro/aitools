import { Button } from '@/components/ui/button';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import { TransportationDetails } from '../dashboard/sales/transportation-details';
import { LoadingSwap } from '../ui/loading-swap';

export default function TransportationForm({
    sale_history_id,
    transportation_details,
    setDialogOpen,
}: {
    sale_history_id: string;
    transportation_details: Record<string, string>;
    setDialogOpen: (open: boolean) => void;
}) {
    // i18n
    const { t } = useTranslation();

    return (
        <Form
            method="put"
            action={route(
                'dashboard.sales.update.transport-details',
                sale_history_id,
            )}
            resetOnSuccess
            options={{ preserveScroll: true }}
            onSuccess={() => {
                setDialogOpen(false);
                toast.success(t('Success!'));
            }}
        >
            {({ processing, errors }) => (
                <div className="space-y-5">
                    <TransportationDetails
                        transportation_details={transportation_details ?? {}}
                        mdGridCols={2}
                        lgGridCols={2}
                    />
                    {/* Submit */}
                    <Button disabled={processing}>
                        <LoadingSwap isLoading={processing}>
                            {t('Update')}
                        </LoadingSwap>
                    </Button>
                </div>
            )}
        </Form>
    );
}
