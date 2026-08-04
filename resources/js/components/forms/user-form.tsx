import { Button } from '@/components/ui/button';
import { FieldType, User } from '@/types';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import DynamicRenderFields from '../form/dynamic-render-fields';
import { Checkbox } from '../ui/checkbox';
import { Field, FieldGroup, FieldLabel } from '../ui/field';
import { LoadingSwap } from '../ui/loading-swap';

export default function UserForm({ user }: { user?: User }) {
    // i18n
    const { t } = useTranslation();
    const isEdit = !!user;

    // permissions
    const permissions = [
        {
            id: 'overview',
            label: 'Overview',
            value: 'overview',
            checked: true,
            disabled: true,
        },
        {
            id: 'customers',
            label: 'Customers',
            value: 'customers',
        },
        {
            id: 'suppliers',
            label: 'Suppliers',
            value: 'suppliers',
        },
        {
            id: 'measurement-units',
            label: 'Measurement Units',
            value: 'measurement-units',
        },
        {
            id: 'products',
            label: 'Products',
            value: 'products',
        },
        {
            id: 'inventory',
            label: 'Inventory',
            value: 'inventory',
        },
        {
            id: 'purchases',
            label: 'Purchases',
            value: 'purchases',
        },
        {
            id: 'sales',
            label: 'Sales',
            value: 'sales',
        },
        {
            id: 'productions',
            label: 'Productions',
            value: 'productions',
        },
        {
            id: 'expenses',
            label: 'Expenses',
            value: 'expenses',
        },
        {
            id: 'calculator',
            label: 'Calculator',
            value: 'calculator',
        },
        {
            id: 'companies',
            label: 'Companies',
            value: 'companies',
        },
        {
            id: 'users',
            label: 'Users',
            value: 'users',
        },
        {
            id: 'settings',
            label: 'Settings',
            value: 'settings',
        },
    ];

    const fields: FieldType[] = [
        {
            id: 'name',
            label: 'Name',
            fieldType: 'input',
            props: {
                placeholder: 'Full name',
                defaultValue: user?.name,
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
                defaultValue: user?.email,
                autoComplete: 'new-email',
                required: true,
            },
        },
        {
            id: 'password',
            label: 'Password',
            fieldType: 'input',
            props: {
                placeholder: isEdit
                    ? t('Leave blank to keep current password')
                    : 'Password',
                autoComplete: 'new-password',
                minLength: 8,
                className: 'pr-10',
                required: !isEdit,
            },
            password: true,
        },
        {
            id: 'phone',
            label: 'Phone',
            fieldType: 'input',
            props: {
                type: 'tel',
                placeholder: 'Phone',
                defaultValue: user?.phone,
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
    ];

    return (
        <Form
            method={isEdit ? 'put' : 'post'}
            action={
                isEdit
                    ? route('dashboard.users.update', user.user_id)
                    : route('dashboard.users.store')
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
                        <DynamicRenderFields fields={fields} errors={errors} />
                    </div>

                    {/* Permissions */}
                    <div className="w-full rounded-lg border p-4 lg:w-1/3">
                        <h3 className="mb-4 font-medium">{t('Permissions')}</h3>
                        <FieldGroup className="-space-y-3">
                            {permissions.map((permission) => (
                                <Field
                                    key={permission.id}
                                    orientation="horizontal"
                                >
                                    {permission.disabled && (
                                        <input
                                            type="hidden"
                                            name="permissions[]"
                                            value={permission.value}
                                        />
                                    )}

                                    <Checkbox
                                        id={permission.id}
                                        name="permissions[]"
                                        value={permission.value}
                                        disabled={permission.disabled}
                                        defaultChecked={
                                            permission.disabled ||
                                            user?.permissions?.includes(
                                                permission.value,
                                            )
                                        }
                                    />
                                    <FieldLabel htmlFor={permission.id}>
                                        {permission.label}
                                    </FieldLabel>
                                </Field>
                            ))}
                        </FieldGroup>
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
