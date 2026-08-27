import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import { LaravelPagination, type BreadcrumbItem } from "@/types";
import { Blog } from "@/types/admin";
import { Head, router } from "@inertiajs/react";
import { useMemo, useState } from "react";
import { useTranslation } from "react-i18next";
import { CheckCircle, Plus, Trash2, XCircle } from "lucide-react";

import { DataTable } from "@/components/table/data-table";
import { Button } from "@/components/ui/button";

import { getColumns } from "./columns";
import { ConfirmDialog } from "@/components/admin/confirm-dialog";
import { toast } from "sonner";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Blogs",
        href: "#",
    },
];

export default function Index({ blogs }: { blogs: LaravelPagination<Blog> }) {
    const { t } = useTranslation();
    const [confirmOpen, setConfirmOpen] = useState(false);

    const [actionLoading, setActionLoading] = useState(false);

    const [selectedBlog, setSelectedBlog] = useState<Blog | null>(null);

    const [selectedAction, setSelectedAction] = useState<
        "publish" | "unpublish" | "delete" | null
    >(null);

    const openActionDialog = (
        blog: Blog,
        action: "publish" | "unpublish" | "delete",
    ) => {
        setSelectedBlog(blog);
        setSelectedAction(action);
        setConfirmOpen(true);
    };

    const columns = useMemo(
        () =>
            getColumns({
                t,

                onEdit: (blog) => {
                    router.get(
                        route("dashboard.admin.edit.blog", blog.blog_id),
                    );
                },

                onAction: openActionDialog,
            }),
        [t],
    );

    const dialogContent = {
        publish: {
            icon: <CheckCircle className="size-7 text-green-600" />,
            title: t("Publish blog?"),
            description: t("If you proceed, this blog will be published."),
            confirmLabel: t("Yes, publish"),
        },

        unpublish: {
            icon: <XCircle className="size-7" />,
            title: t("Unpublish blog?"),
            description: t("If you proceed, this blog will be unpublished."),
            confirmLabel: t("Yes, unpublish"),
        },

        delete: {
            icon: <Trash2 className="size-7 text-destructive" />,
            title: t("Delete blog?"),
            description: t("If you proceed, this blog will be deleted."),
            confirmLabel: t("Yes, delete"),
        },
    };

    const currentDialog = selectedAction ? dialogContent[selectedAction] : null;

    const handleAction = () => {
        if (!selectedBlog || !selectedAction) {
            return;
        }

        setActionLoading(true);

        router.get(
            route("dashboard.admin.action.blog"),
            {
                id: selectedBlog.blog_id,
                mode: selectedAction,
            },
            {
                preserveScroll: true,

                onSuccess: () => {
                    const messages = {
                        publish: t("Blog published successfully!"),
                        unpublish: t("Blog unpublished successfully!"),
                        delete: t("Blog deleted successfully!"),
                    };

                    toast.success(messages[selectedAction]);

                    setConfirmOpen(false);
                    setSelectedBlog(null);
                    setSelectedAction(null);
                },

                onError: () => {
                    toast.error(t("Unable to update blog."));
                },

                onFinish: () => {
                    setActionLoading(false);
                },
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Blogs")} />

            <div className="mb-4 flex items-start justify-between">
                <Heading
                    title={t("Blogs")}
                    description={t("Create and manage your blog posts")}
                />

                <Button
                    onClick={() =>
                        router.get(route("dashboard.admin.create.blog"))
                    }
                >
                    <Plus />
                    {t("Create")}
                </Button>
            </div>

            <DataTable
                columns={columns}
                data={blogs.data}
                pageIndex={blogs.current_page - 1}
                pageSize={blogs.per_page}
                totalCount={blogs.total}
                initialSearch={route().params.search ?? ""}
                onPageChange={(page) =>
                    router.get(
                        route("dashboard.admin.blogs.post"),
                        {
                            page: page + 1,
                            per_page: blogs.per_page,
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
                        route("dashboard.admin.blogs.post"),
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
                        route("dashboard.admin.blogs.post"),
                        {
                            page: 1,
                            per_page: blogs.per_page,
                            search,
                        },
                        {
                            preserveState: true,
                            preserveScroll: true,
                        },
                    )
                }
            />
            {selectedAction && (
                <ConfirmDialog
                    open={confirmOpen}
                    onOpenChange={setConfirmOpen}
                    icon={dialogContent[selectedAction].icon}
                    title={dialogContent[selectedAction].title}
                    description={dialogContent[selectedAction].description}
                    cancelLabel={t("Cancel")}
                    confirmLabel={dialogContent[selectedAction].confirmLabel}
                    onConfirm={handleAction}
                    loading={actionLoading}
                />
            )}
        </AppLayout>
    );
}
