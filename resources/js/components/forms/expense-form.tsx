import { Button } from '@/components/ui/button';
import { Company, Expense, ExpenseCategory, FieldType } from '@/types';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import DynamicRenderFields from '../form/dynamic-render-fields';
import { LoadingSwap } from '../ui/loading-swap';

export default function ExpenseForm({
    setActionDialogOpen,
    expense_categories,
    companies,
    expense,
}: {
    setActionDialogOpen: (open: boolean) => void;
    expense_categories: ExpenseCategory[];
    companies: Company[];
    expense?: Expense;
}) {
    // auth
    const { t } = useTranslation();
    const isEdit = !!expense;

    const fields: FieldType[] = [
        {
            id: 'expense_category_id',
            label: 'Expense Category',
            fieldType: 'select',
            props: {
                defaultValue: expense?.expense_category_id?.toString(),
                placeholder: 'Select',
                options: expense_categories.map((expense) => ({
                    label: expense.category_name,
                    value: expense.expense_category_id.toString(),
                })),
                required: true,
            },
        },
        {
            id: 'company_id',
            label: 'Company',
            fieldType: 'select',
            props: {
                defaultValue: expense?.company_id?.toString(),
                placeholder: 'Select',
                options: companies.map((company) => ({
                    label: company.company_name,
                    value: company.company_id.toString(),
                })),
                required: true,
            },
        },
        {
            id: 'notes',
            label: 'Notes',
            fieldType: 'input',
            props: {
                defaultValue: expense?.notes,
                placeholder: 'Notes',
            },
        },
        {
            id: 'amount',
            label: 'Amount',
            fieldType: 'input-group',
            props: {
                type: 'number',
                min: 0,
                step: '0.01',
                defaultValue: expense?.amount ?? 0,
                placeholder: 'Amount',
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
    ];

    return (
        <Form
            method={isEdit ? 'put' : 'post'}
            action={
                isEdit
                    ? route('dashboard.expenses.update', expense.expense_id)
                    : route('dashboard.expenses.store')
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
                    <div className="grid grid-cols-1 items-start gap-6 md:grid-cols-2">
                        <DynamicRenderFields
                            fields={fields}
                            errors={errors}
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
