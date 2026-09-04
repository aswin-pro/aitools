import Heading from "@/components/heading";
import { DataTable } from "@/components/table/data-table";
import { Button } from "@/components/ui/button";
import AppLayout from "@/layouts/app/app-layout";
import {
    LaravelPagination,
    NavigateParams,
    type BreadcrumbItem,
} from "@/types";
import { CustomTemplateCategory } from "@/types/admin";
import { Head, router, useForm } from "@inertiajs/react";
import { CheckCircle, Plus, Trash2, XCircle } from "lucide-react";
import { useMemo, useState } from "react";
import { useTranslation } from "react-i18next";
import { getColumns } from "./columns";
import { toast } from "sonner";
import { FormSheet } from "@/components/admin/form-sheet";
import { ConfirmDialog } from "@/components/admin/confirm-dialog";

interface IndexProps {
    categories: LaravelPagination<CustomTemplateCategory>;
    filters?: {
        search?: string;
        per_page?: number;
    };
}

export default function Index({ categories, filters }: IndexProps) {
    const { t } = useTranslation();

    const [editOpen, setEditOpen] = useState(false);

    const [selectedCategory, setSelectedCategory] =
        useState<CustomTemplateCategory | null>(null);

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: "Dashboard",
            href: route("dashboard.admin.overview"),
        },
        {
            title: "Content Templates",
            href: route("dashboard.admin.overview"),
        },
        {
            title: "Categories",
            href: "#",
        },
    ];

    const [selectedAction, setSelectedAction] = useState<
        "active" | "inactive" | "delete" | null
    >(null);

    const [actionLoading, setActionLoading] = useState(false);

    const [confirmOpen, setConfirmOpen] = useState(false);
    const [createOpen, setCreateOpen] = useState(false);

    const openActionDialog = (
        category: CustomTemplateCategory,
        action: "active" | "inactive" | "delete",
    ) => {
        setSelectedCategory(category);
        setSelectedAction(action);
        setConfirmOpen(true);
    };

    const createForm = useForm({
        category_name: "",
    });

    const editForm = useForm({
        category_id: "",
        category_name: "",
    });

    const navigate = (params: NavigateParams) => {
        router.reload({
            only: ["categories"],
            data: params,
        });
    };

    const handleCreateSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        createForm.post(route("dashboard.admin.save.category"), {
            preserveScroll: true,

            onSuccess: () => {
                setCreateOpen(false);
                createForm.reset();

                toast.success(t("Category created successfully!"));
            },

            onError: () => {
                toast.error(t("Unable to create category."));
            },
        });
    };

    const handleEdit = (category: CustomTemplateCategory) => {
        setSelectedCategory(category);

        editForm.setData({
            category_id: String(category.id),
            category_name: category.category_name,
        });

        editForm.clearErrors();

        setEditOpen(true);
    };

    const handleEditSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        editForm.post(route("dashboard.admin.update.category"), {
            preserveScroll: true,

            onSuccess: () => {
                setEditOpen(false);
                setSelectedCategory(null);
                editForm.reset();

                toast.success(t("Category updated successfully!"));
            },

            onError: () => {
                toast.error(t("Unable to update category."));
            },
        });
    };

    const handleAction = () => {
        if (!selectedCategory || !selectedAction) return;

        setActionLoading(true);

        router.get(
            route("dashboard.admin.delete.category"),
            {
                id: selectedCategory.id,
                action: selectedAction,
            },
            {
                preserveScroll: true,
                preserveState: true,

                onSuccess: () => {
                    setConfirmOpen(false);

                    if (selectedAction === "active") {
                        toast.success(t("Category activated successfully!"));
                    } else if (selectedAction === "inactive") {
                        toast.success(t("Category deactivated successfully!"));
                    } else if (selectedAction === "delete") {
                        toast.success(t("Category deleted successfully!"));
                    }

                    setSelectedCategory(null);
                    setSelectedAction(null);

                    // Reload the table data
                    router.reload({
                        only: ["categories"],
                        onFinish: () => {
                            setActionLoading(false);
                        },
                    });
                    setActionLoading(false);
                },

                onError: (errors) => {
                    setConfirmOpen(false);
                    setSelectedCategory(null);
                    setSelectedAction(null);
                    setActionLoading(false);

                    toast.error(
                        errors.action ?? t("Unable to update category."),
                    );
                },
            },
        );
    };

    const columns = useMemo(
        () =>
            getColumns({
                pageIndex: categories.current_page - 1,
                pageSize: categories.per_page,
                t,
                onEdit: handleEdit,
                onAction: openActionDialog,
            }),
        [categories.current_page, categories.per_page, t],
    );

    const dialogContent = {
        active: {
            icon: <CheckCircle className="size-7 text-green-600" />,
            title: t("Set category as active?"),
            description: t("If you proceed, this category will be active."),
            confirmLabel: t("Yes, set active"),
        },

        inactive: {
            icon: <XCircle className="size-7 text-destructive" />,
            title: t("Set category as inactive?"),
            description: t("If you proceed, this category will be inactive."),
            confirmLabel: t("Yes, set inactive"),
        },

        delete: {
            icon: <Trash2 className="size-7 text-destructive" />,
            title: t("Delete category?"),
            description: t("If you proceed, this category will be deleted."),
            confirmLabel: t("Yes, delete"),
        },
    };

    const currentDialog = selectedAction ? dialogContent[selectedAction] : null;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Template Categories")} />

            <div className="mb-4 flex items-start justify-between">
                <Heading
                    title={t("Template Categories")}
                    description={t(
                        "Create and manage your Template categories",
                    )}
                />

                <Button onClick={() => setCreateOpen(true)}>
                    <Plus />
                    {t("Create")}
                </Button>
            </div>

            <DataTable
                columns={columns}
                data={categories.data}
                pageIndex={categories.current_page - 1}
                pageSize={categories.per_page}
                totalCount={categories.total}
                initialSearch={route().params.search ?? ""}
                onPageChange={(page) =>
                    navigate({
                        page: page + 1,
                        per_page: categories.per_page,
                        search: route().params.search,
                    })
                }
                onPageSizeChange={(size) =>
                    navigate({
                        page: 1,
                        per_page: size,
                        search: route().params.search,
                    })
                }
                onSearch={(search) =>
                    navigate({
                        page: 1,
                        search,
                    })
                }
            />

            {/* for create  */}
            <FormSheet
                open={createOpen}
                onOpenChange={setCreateOpen}
                title={t("Create Category")}
                description={t("Create a new template category")}
                form={createForm}
                fields={[
                    {
                        type: "input",
                        name: "category_name",
                        label: t("Category Name"),
                        placeholder: t("Enter category name"),
                        required: true,
                    },
                ]}
                onSubmit={handleCreateSubmit}
                submitLabel={t("Create")}
                cancelLabel={t("Cancel")}
            />

            {/* for edit  */}
            <FormSheet
                open={editOpen}
                onOpenChange={setEditOpen}
                title={t("Edit Category")}
                description={t("Update the category details")}
                form={editForm}
                fields={[
                    {
                        type: "input",
                        name: "category_name",
                        label: t("Category Name"),
                        placeholder: t("Enter category name"),
                        required: true,
                    },
                ]}
                onSubmit={handleEditSubmit}
                submitLabel={t("Update")}
                cancelLabel={t("Cancel")}
            />

            {currentDialog && (
                <ConfirmDialog
                    open={confirmOpen}
                    onOpenChange={setConfirmOpen}
                    icon={currentDialog.icon}
                    title={currentDialog.title}
                    description={currentDialog.description}
                    cancelLabel={t("Cancel")}
                    confirmLabel={currentDialog.confirmLabel}
                    onConfirm={handleAction}
                    loading={actionLoading}
                />
            )}
        </AppLayout>
    );
}
