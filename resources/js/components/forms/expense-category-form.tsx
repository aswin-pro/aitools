import { Button } from '@/components/ui/button';
import { ExpenseCategory, FieldType } from '@/types';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import DynamicRenderFields from '../form/dynamic-render-fields';
import { LoadingSwap } from '../ui/loading-swap';

export default function ExpenseCategoryForm({
    setActionDialogOpen,
    category,
}: {
    setActionDialogOpen: (open: boolean) => void;
    category?: ExpenseCategory;
}) {
    // auth
    const { t } = useTranslation();
    const isEdit = !!category;

    const fields: FieldType[] = [
        {
            id: 'category_name',
            label: 'Category Name',
            fieldType: 'input',
            props: {
                defaultValue: category?.category_name,
                placeholder: 'Category Name',
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
                          'dashboard.expenses.categories.update',
                          category.expense_category_id,
                      )
                    : route('dashboard.expenses.categories.store')
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
