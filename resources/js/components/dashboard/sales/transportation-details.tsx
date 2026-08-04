import DynamicRenderFields from '@/components/form/dynamic-render-fields';
import { FieldType } from '@/types';
import { useState } from 'react';

export function TransportationDetails({
    transportation_details,
    mdGridCols,
    lgGridCols,
}: {
    transportation_details: Record<string, string>;
    mdGridCols: number;
    lgGridCols: number;
}) {
    const [dates, setDates] = useState<Record<string, Date | undefined>>({
        dispatch_date: transportation_details?.dispatch_date
            ? new Date(transportation_details.dispatch_date)
            : undefined,
    });

    const transportationFields: FieldType[] = [
        {
            id: 'lr_no',
            label: 'LR / Consignment No',
            fieldType: 'input',
            props: {
                placeholder: 'LR Number',
                defaultValue: transportation_details?.lr_no,
            },
        },
        {
            id: 'vehicle_no',
            label: 'Vehicle No',
            fieldType: 'input',
            props: {
                placeholder: 'Vehicle No',
                defaultValue: transportation_details?.vehicle_no,
            },
        },
        {
            id: 'transporter_name',
            label: 'Transporter Name',
            fieldType: 'input',
            props: {
                placeholder: 'Transporter Name',
                defaultValue: transportation_details?.transporter_name,
            },
        },
        {
            id: 'driver_name',
            label: 'Driver Name',
            fieldType: 'input',
            props: {
                placeholder: 'Driver Name',
                defaultValue: transportation_details?.driver_name,
            },
        },
        {
            id: 'driver_mobile',
            label: 'Driver Mobile Number',
            fieldType: 'input',
            props: {
                type: 'tel',
                placeholder: 'Driver Mobile Number',
                defaultValue: transportation_details?.driver_mobile,
                inputMode: 'numeric',
                pattern: '[0-9]*',
                onInput: (e: React.FormEvent<HTMLInputElement>) => {
                    e.currentTarget.value = e.currentTarget.value.replace(
                        /\D/g,
                        '',
                    );
                },
            },
        },
        {
            id: 'dispatch_date',
            label: 'Dispatch Date',
            fieldType: 'date-picker',
            props: {
                placeholder: 'Select',
                defaultValue: transportation_details?.dispatch_date,
            },
        },
        {
            id: 'transport_status',
            label: 'Transport Status',
            fieldType: 'select',
            props: {
                placeholder: 'Select',
                options: [
                    { label: 'Pending', value: 'pending' },
                    { label: 'Dispatched', value: 'dispatched' },
                    { label: 'In Transit', value: 'in_transit' },
                    { label: 'Delivered', value: 'delivered' },
                ],
                defaultValue:
                    transportation_details?.transport_status ?? 'pending',
            },
        },
    ];

    return (
        <div
            className={`grid grid-cols-1 items-start gap-6 md:grid-cols-${mdGridCols} lg:grid-cols-${lgGridCols}`}
        >
            <DynamicRenderFields
                fields={transportationFields}
                errors={{}}
                dates={dates}
                setDates={setDates}
            />
        </div>
    );
}
