import { Button } from '@/components/ui/button';
import { FieldType, ProductCategory } from '@/types';
import { Form } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { toast } from 'sonner';
import DynamicRenderFields from '../form/dynamic-render-fields';
import { LoadingSwap } from '../ui/loading-swap';

export default function ProductCategoryForm({
    setActionDialogOpen,
    category,
}: {
    setActionDialogOpen: (open: boolean) => void;
    category?: ProductCategory;
}) {
    // auth
    const { t } = useTranslation();
    const isEdit = !!category;

    const colorOptions = [
        { label: 'Red', value: 'bg-red-500' },
        { label: 'Orange', value: 'bg-orange-500' },
        { label: 'Yellow', value: 'bg-yellow-500' },
        { label: 'Green', value: 'bg-green-500' },
        { label: 'Sky Blue', value: 'bg-sky-500' },
        { label: 'Blue', value: 'bg-blue-500' },
        { label: 'Indigo', value: 'bg-indigo-500' },
        { label: 'Violet', value: 'bg-violet-500' },
        { label: 'Purple', value: 'bg-purple-500' },
        { label: 'Fuchsia', value: 'bg-fuchsia-500' },
        { label: 'Pink', value: 'bg-pink-500' },
    ];

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
        {
            id: 'color',
            label: 'Color',
            fieldType: 'select',
            props: {
                defaultValue: category?.color?.toString() ?? 'bg-blue-500',
                placeholder: 'Select',
                options: colorOptions.map((option) => ({
                    label: option.label,
                    value: option.value.toString(),
                })),
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
                          'dashboard.products.categories.update',
                          category.product_category_id,
                      )
                    : route('dashboard.products.categories.store')
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
