import { Button } from '@/components/ui/button';
import { Employee, FieldType } from '@/types';
import { Form } from '@inertiajs/react';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import { LoadingSwap } from '../ui/loading-swap';
import DynamicRenderFields from '../form/dynamic-render-fields';

export default function EmployeeForm({ employee }: { employee?: Employee }) {
    // i18n
    const { t } = useTranslation();
    const isEdit = !!employee;

    const [dates, setDates] = useState<Record<string, Date | undefined>>({
        dob: employee?.dob ? new Date(employee.dob) : undefined,
    });

    const fields: FieldType[] = [
        {
            id: 'name',
            label: 'Employee Name',
            fieldType: 'input',
            props: {
                placeholder: 'Full name',
                defaultValue: employee?.name,
                minLength: 4,
                required: true,
            },
        },
        {
            id: 'email',
            label: 'Email address',
            fieldType: 'input',
            props: {
                type: 'email',
                placeholder: 'email@example.com',
                defaultValue: employee?.email,
                autoComplete: 'new-email',
            },
        },
        {
            id: 'mobile_number',
            label: 'Mobile Number',
            fieldType: 'input',
            props: {
                type: 'tel',
                placeholder: 'Mobile Number',
                defaultValue: employee?.mobile_number,
                inputMode: 'numeric',
                pattern: '[0-9]*',
                onInput: (e: React.FormEvent<HTMLInputElement>) => {
                    e.currentTarget.value = e.currentTarget.value.replace(
                        /\D/g,
                        '',
                    );
                },
                required: true,
            },
        },
        {
            id: 'alternative_mobile_number',
            label: 'Alternative Mobile Number',
            fieldType: 'input',
            props: {
                type: 'tel',
                placeholder: 'Alternative Mobile Number',
                defaultValue: employee?.alternative_mobile_number,
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
            id: 'address',
            label: 'Address',
            fieldType: 'input',
            props: {
                placeholder: 'Address',
                defaultValue: employee?.address,
            },
        },
        {
            id: 'employee_type',
            label: 'Employee Type',
            fieldType: 'select',
            props: {
                defaultValue: employee?.employee_type?.toString() ?? 'contract',
                placeholder: 'Select',
                options: [
                    { label: 'Contract', value: 'contract' },
                    { label: 'Permanent', value: 'permanent' },
                ],
                required: true,
            },
        },
        {
            id: 'gender',
            label: 'Gender',
            fieldType: 'select',
            props: {
                defaultValue: employee?.gender ?? 'male',
                placeholder: 'Select',
                options: [
                    { label: 'Male', value: 'male' },
                    { label: 'Female', value: 'female' },
                    { label: 'Transgender', value: 'transgender' },
                ],
                required: true,
            },
        },
        {
            id: 'dob',
            label: 'Date of Birth',
            fieldType: 'date-picker',
            props: {
                placeholder: 'Select',
            },
        },
        {
            id: 'per_day_wage',
            label: 'Per Day Wage',
            fieldType: 'input-group',
            props: {
                type: 'number',
                min: 0,
                step: '0.01',
                defaultValue: employee?.per_day_wage ?? 0,
                placeholder: 'Per Day Wage',
                onKeyDown: (e: React.KeyboardEvent<HTMLInputElement>) => {
                    if (e.key === 'e' || e.key === 'E') {
                        e.preventDefault();
                    }
                },
                required: true,
            },
            inputGroup: {
                align: 'inline-start',
                content: '₹',
            },
        },
        {
            id: 'aadhaar_number',
            label: 'Aadhaar Number',
            fieldType: 'input',
            props: {
                placeholder: 'Aadhaar Number',
                defaultValue: employee?.aadhaar_number,
            },
        },
        {
            id: 'pan_number',
            label: 'PAN Number',
            fieldType: 'input',
            props: {
                placeholder: 'PAN Number',
                defaultValue: employee?.pan_number,
                required: false,
            },
        },
    ];

    return (
        <Form
            method={isEdit ? 'put' : 'post'}
            action={
                isEdit
                    ? route('dashboard.employees.update', employee.employee_id)
                    : route('dashboard.employees.store')
            }
            resetOnSuccess={!isEdit}
            options={{ preserveScroll: true }}
            onSuccess={() => {
                toast.success(t('Success!'));
            }}
        >
            {({ processing, errors }) => (
                <div className="space-y-5">
                    <div className="grid grid-cols-1 items-start gap-6 md:grid-cols-4">
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
                </div>
            )}
        </Form>
    );
}
