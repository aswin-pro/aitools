import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import { LaravelPagination, type BreadcrumbItem } from "@/types";
import { Head, router, useForm } from "@inertiajs/react";
import { useMemo, useState } from "react";
import { useTranslation } from "react-i18next";
import { DataTable } from "@/components/table/data-table";
import { Button } from "@/components/ui/button";
import { Plus } from "lucide-react";
import { ConfirmDialog } from "@/components/admin/confirm-dialog";
import { CheckCircle, Trash2, XCircle } from "lucide-react";
import { getColumns } from "./columns";
import { BlogCategory } from "@/types/admin";
import { FormSheet } from "@/components/admin/form-sheet";
import { toast } from "sonner";

export default function Index({
    blogsCategories,
}: {
    blogsCategories: LaravelPagination<BlogCategory>;
}) {
    const { t } = useTranslation();

    const breadcrumbs: BreadcrumbItem[] = [
        { title: t("Dashboard"), href: route("dashboard.admin.overview") },
        { title: t("Blogs"), href: route("dashboard.admin.blogs.post") },
        { title: t("Categories"), href: "#" },
    ];

    const [actionLoading, setActionLoading] = useState(false);
    const [confirmOpen, setConfirmOpen] = useState(false);
    const [createOpen, setCreateOpen] = useState(false);
    const [editOpen, setEditOpen] = useState(false);

    const [selectedCategory, setSelectedCategory] =
        useState<BlogCategory | null>(null);

    const [selectedAction, setSelectedAction] = useState<
        "publish" | "unpublish" | "delete" | null
    >(null);

    const openActionDialog = (
        category: BlogCategory,
        action: "publish" | "unpublish" | "delete",
    ) => {
        setSelectedCategory(category);
        setSelectedAction(action);
        setConfirmOpen(true);
    };

    const form = useForm({
        category_name: "",
        category_slug: "",
    });

    const editForm = useForm({
        category_name: "",
        category_slug: "",
    });

    const handleCreate = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        form.post(route("dashboard.admin.publish.blog.category"), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success(t("Category created successfully!"));
                setCreateOpen(false);
                form.reset();
            },
            onError: () => {
                toast.error(t("Failed to create category."));
            },
        });
    };

    const handleAction = () => {
        if (!selectedCategory || !selectedAction) {
            return;
        }

        setActionLoading(true);

        router.get(
            route("dashboard.admin.action.blog.category"),
            {
                id: selectedCategory.blog_category_id,
                mode: selectedAction,
            },
            {
                preserveScroll: true,

                onSuccess: () => {
                    const messages = {
                        publish: t("Category published successfully!"),
                        unpublish: t("Category unpublished successfully!"),
                        delete: t("Category deleted successfully!"),
                    };

                    toast.success(messages[selectedAction]);

                    setConfirmOpen(false);
                    setSelectedCategory(null);
                    setSelectedAction(null);
                },

                onError: () => {
                    setActionLoading(false);
                },

                onFinish: () => {
                    setActionLoading(false);
                },
            },
        );
    };

    const handleEdit = (category: BlogCategory) => {
        setSelectedCategory(category);

        editForm.setData({
            category_name: category.blog_category_title,
            category_slug: category.blog_category_slug,
        });

        setEditOpen(true);
    };

    const handleEditSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        if (!selectedCategory) {
            return;
        }

        editForm.post(
            route(
                "dashboard.admin.update.blog.category",
                selectedCategory.blog_category_id,
            ),
            {
                preserveScroll: true,

                onSuccess: () => {
                    toast.success(t("Category updated successfully!"));
                    setEditOpen(false);
                    setSelectedCategory(null);
                    editForm.reset();
                },

                onError: () => {
                    toast.error(t("Failed to update category."));
                },
            },
        );
    };

    const columns = useMemo(
        () =>
            getColumns({
                pageIndex: 0,
                t,
                onEdit: handleEdit,
                onAction: openActionDialog,
            }),
        [t],
    );

    const dialogContent = {
        publish: {
            icon: <CheckCircle className="size-7 text-green-600" />,
            title: t("Publish category?"),
            description: t(
                "If you proceed, this blog category will be published.",
            ),
            confirmLabel: t("Yes, publish"),
        },

        unpublish: {
            icon: <XCircle className="size-7 " />,
            title: t("Unpublish category?"),
            description: t(
                "If you proceed, this blog category will be unpublished.",
            ),
            confirmLabel: t("Yes, unpublish"),
        },

        delete: {
            icon: <Trash2 className="size-7 text-destructive" />,
            title: t("Delete category?"),
            description: t(
                "If you proceed, this blog category will be deleted.",
            ),
            confirmLabel: t("Yes, delete"),
        },
    };

    const currentDialog = selectedAction ? dialogContent[selectedAction] : null;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Blog Categories")} />

            <div className="mb-4 flex items-start justify-between">
                <Heading
                    title={t("Blog Categories")}
                    description={t("Create and manage your blog categories")}
                />

                <Button onClick={() => setCreateOpen(true)}>
                    <Plus />
                    {t("Create")}
                </Button>
            </div>

            {/* Table */}
            <DataTable
                columns={columns}
                data={blogsCategories.data}
                pageIndex={blogsCategories.current_page - 1}
                pageSize={blogsCategories.per_page}
                totalCount={blogsCategories.total}
                initialSearch={route().params.search ?? ""}
                onPageChange={(page) =>
                    router.get(
                        route("dashboard.admin.blog.categories"),
                        {
                            page: page + 1,
                            per_page: blogsCategories.per_page,
                            search: route().params.search,
                        },
                        {
                            preserveState: true,
                            preserveScroll: true,
                        },
                    )
                }
                onPageSizeChange={(size) =>
                    router.get(
                        route("dashboard.admin.blog.categories"),
                        {
                            page: 1,
                            per_page: size,
                            search: route().params.search,
                        },
                        {
                            preserveState: true,
                            preserveScroll: true,
                        },
                    )
                }
                onSearch={(search) =>
                    router.get(
                        route("dashboard.admin.blog.categories"),
                        {
                            page: 1,
                            per_page: blogsCategories.per_page,
                            search,
                        },
                        {
                            preserveState: true,
                            preserveScroll: true,
                        },
                    )
                }
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

            <FormSheet
                open={createOpen}
                onOpenChange={setCreateOpen}
                title={t("Create Category")}
                description={t("Create a new blog category")}
                form={form}
                fields={[
                    {
                        type: "input",
                        name: "category_name",
                        label: t("Category Name"),
                        placeholder: t("Enter category name"),
                        required: true,
                    },
                    {
                        type: "input",
                        name: "category_slug",
                        label: t("Category Slug"),
                        placeholder: t("Enter category slug"),
                        required: true,
                    },
                ]}
                onSubmit={handleCreate}
                submitLabel={t("Create")}
                cancelLabel={t("Cancel")}
            />

            <FormSheet
                open={editOpen}
                onOpenChange={setEditOpen}
                title={t("Edit Category")}
                description={t("Edit a blog category")}
                form={editForm}
                fields={[
                    {
                        type: "input",
                        name: "category_name",
                        label: t("Category Name"),
                        placeholder: t("Enter category name"),
                        required: true,
                    },
                    {
                        type: "input",
                        name: "category_slug",
                        label: t("Category Slug"),
                        placeholder: t("Enter category slug"),
                        required: true,
                    },
                ]}
                onSubmit={handleEditSubmit}
                submitLabel={t("Edit")}
                cancelLabel={t("Cancel")}
            />
        </AppLayout>
    );
}
