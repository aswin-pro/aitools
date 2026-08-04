import { Button } from '@/components/ui/button';
import { FieldType, MeasurementUnit } from '@/types';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import DynamicRenderFields from '../form/dynamic-render-fields';
import { LoadingSwap } from '../ui/loading-swap';

export default function MeasurementUnitForm({
    setActionDialogOpen,
    unit,
}: {
    setActionDialogOpen: (open: boolean) => void;
    unit?: MeasurementUnit;
}) {
    // auth
    const { t } = useTranslation();
    const isEdit = !!unit;

    const fields: FieldType[] = [
        {
            id: 'unit_name',
            label: 'Measurement Name',
            fieldType: 'input',
            props: {
                defaultValue: unit?.unit_name,
                placeholder: 'Ex: Kilogram',
                required: true,
            },
        },
        {
            id: 'unit',
            label: 'Measurement Unit',
            fieldType: 'input',
            props: {
                defaultValue: unit?.unit,
                placeholder: 'Ex: kg',
                required: true,
            },
        },
    ];

    return (
        <Form
            method={isEdit ? 'put' : 'post'}
            action={
                isEdit
                    ? route(
                          'dashboard.measurement-units.update',
                          unit.measurement_unit_id,
                      )
                    : route('dashboard.measurement-units.store')
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
                        <DynamicRenderFields fields={fields} errors={errors} />
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
