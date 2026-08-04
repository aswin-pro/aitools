import { Button } from '@/components/ui/button';
import { roundToTwoDecimals } from '@/helpers/format-number';
import { FieldType, PurchasePayment } from '@/types';
import { Form } from '@inertiajs/react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import DynamicRenderFields from '../form/dynamic-render-fields';
import { LoadingSwap } from '../ui/loading-swap';

export default function PurchasePaymentForm({
    setActionDialogOpen,
    due_amount,
    payment,
}: {
    setActionDialogOpen: (open: boolean) => void;
    due_amount: number;
    payment?: PurchasePayment;
}) {
    // auth
    const { t } = useTranslation();
    const isEdit = !!payment;

    const [dates, setDates] = useState<Record<string, Date | undefined>>({
        payment_date: payment?.payment_date
            ? new Date(payment.payment_date)
            : new Date(),
    });

    const originalAmount = roundToTwoDecimals(payment?.amount ?? 0);
    const [amount, setAmount] = useState(roundToTwoDecimals(originalAmount));

    const paymentModes = [
        { label: 'Cash', value: 'cash' },
        { label: 'Online', value: 'online' },
        { label: 'Cheque', value: 'cheque' },
    ];

    const fields: FieldType[] = [
        {
            id: 'payment_mode',
            fieldType: 'select',
            label: 'Payment Mode',
            props: {
                defaultValue: payment?.payment_mode?.toString() ?? 'cash',
                placeholder: 'Select',
                options: paymentModes.map((mode) => ({
                    label: mode.label,
                    value: mode.value.toString(),
                })),
                required: true,
            },
        },
        {
            id: 'payment_date',
            fieldType: 'date-picker',
            label: 'Payment Date',
            props: {
                placeholder: 'Select',
                required: true,
            },
        },
        {
            id: 'amount',
            fieldType: 'input-group',
            label: 'Amount',
            props: {
                type: 'number',
                defaultValue: amount,
                placeholder: 'Amount',
                min: 0,
                max:
                    amount > originalAmount
                        ? roundToTwoDecimals(
                              roundToTwoDecimals(due_amount) +
                                  roundToTwoDecimals(originalAmount),
                          )
                        : undefined,
                step: '0.01',
                onKeyDown: (e: React.KeyboardEvent<HTMLInputElement>) => {
                    if (e.key === 'e' || e.key === 'E') {
                        e.preventDefault();
                    }
                },
                onChange: (e) => setAmount(roundToTwoDecimals(e.target.value)),
                required: true,
            },
            inputGroup: {
                align: 'inline-start',
                content: '₹',
            },
        },
    ];

    return (
        <Form
            method={isEdit ? 'put' : 'post'}
            action={
                isEdit
                    ? route('dashboard.purchases.payments.update', {
                          purchase: route().params.purchase,
                          payment: payment.purchase_payment_id,
                      })
                    : route(
                          'dashboard.purchases.payments.store',
                          route().params.purchase,
                      )
            }
            resetOnSuccess={!isEdit}
            options={{ preserveScroll: true }}
            className="mt-2 space-y-5"
            onSuccess={() => {
                toast.success(t('Success!'));
                setActionDialogOpen(false);
            }}
        >
            {({ processing, errors }) => (
                <>
                    <div className="grid grid-cols-1 items-start gap-6">
                        <DynamicRenderFields
                            fields={fields}
                            errors={errors}
                            dates={dates}
                            setDates={setDates}
                        />
                    </div>

                    {/* Submit */}
                    <Button disabled={processing}>
                        <LoadingSwap isLoading={processing}>
                            {t(isEdit ? 'Update' : 'Save')}
                        </LoadingSwap>
                    </Button>
                </>
            )}
        </Form>
    );
}
