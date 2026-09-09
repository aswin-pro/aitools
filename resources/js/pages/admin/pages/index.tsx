import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import {
    BreadcrumbItem,
    LaravelPagination,
    NavigateParams,
    SharedData,
} from "@/types";
import { Head, router, usePage } from "@inertiajs/react";
import { useEffect, useMemo, useRef, useState } from "react";
import { useTranslation } from "react-i18next";
import { DataTable } from "@/components/table/data-table";
import { Button } from "@/components/ui/button";
import { Plus, Power, Trash2 } from "lucide-react";
import { toast } from "sonner";
import { ConfirmDialog } from "@/components/admin/confirm-dialog";
import { getColumns, PageItem } from "./columns";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Pages",
        href: "#",
    },
];

export default function Index({
    pages,
    custom_pages,
}: {
    pages: LaravelPagination<PageItem>;
    custom_pages: LaravelPagination<PageItem>;
}) {
    const { t } = useTranslation();

    const [statusDialogOpen, setStatusDialogOpen] = useState(false);
    const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);

    const [pageToChange, setPageToChange] =
        useState<PageItem | null>(null);

    const [pageToDelete, setPageToDelete] =
        useState<PageItem | null>(null);

    const [actionLoading, setActionLoading] = useState(false);

    const { flash } = usePage<SharedData>().props;

    const lastMessage = useRef<string | null>(null);

    useEffect(() => {
        if (flash?.success && lastMessage.current !== flash.success) {
            toast.success(flash.success);
            lastMessage.current = flash.success;
        }

        if (flash?.error && lastMessage.current !== flash.error) {
            toast.error(flash.error);
            lastMessage.current = flash.error;
        }
    }, [flash]);

  

    const navigate = (params: NavigateParams) => {
        router.reload({
            only: ["pages", "custom_pages"],
            data: params,
        });
    };

  
    const staticColumns = useMemo(
        () =>
            getColumns({
                pageIndex: pages.current_page - 1,
                pageSize: pages.per_page,
                t,
                isCustom: false,

                onEdit: (page) => {
                    router.get(
                        route("dashboard.admin.edit.page", page.slug),
                    );
                },

                onStatus: (page) => {
                    setPageToChange(page);
                    setStatusDialogOpen(true);
                },
            }),
        [pages.current_page, pages.per_page, t],
    );


    const customColumns = useMemo(
        () =>
            getColumns({
                pageIndex: custom_pages.current_page - 1,
                pageSize: custom_pages.per_page,
                t,
                isCustom: true,

                onEdit: (page) => {
                    router.get(
                        route("dashboard.admin.edit.custom.page", page.id),
                    );
                },

                onStatus: (page) => {
                    setPageToChange(page);
                    setStatusDialogOpen(true);
                },

                onDelete: (page) => {
                    setPageToDelete(page);
                    setDeleteDialogOpen(true);
                },
            }),
        [custom_pages.current_page, custom_pages.per_page, t],
    );

  

    const handleStatus = () => {
        if (!pageToChange) {
            return;
        }

        setActionLoading(true);

        const isCustomPage =
            pageToChange.name === "Custom Page";

        const routeName = isCustomPage
            ? "dashboard.admin.status.page"
            : "dashboard.admin.disable.page";

        const id = isCustomPage
            ? pageToChange.id
            : pageToChange.slug;

        router.get(
            route(routeName),
            {
                id,
            },
            {
                preserveScroll: true,

                onSuccess: () => {
                    setStatusDialogOpen(false);
                    setPageToChange(null);
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


    const handleDelete = () => {
        if (!pageToDelete) {
            return;
        }

        setActionLoading(true);

        router.get(
            route("dashboard.admin.delete.page"),
            {
                id: pageToDelete.id,
            },
            {
                preserveScroll: true,

                onSuccess: () => {
                    setDeleteDialogOpen(false);
                    setPageToDelete(null);
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

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Pages")} />

            <div className="space-y-6">

             

                <Heading
                t={t}
                    title={t("Pages")}
                    description={t(
                        "Manage website pages and custom pages",
                    )}
                />


                <div className="space-y-3">
                    <Heading
                    t={t}
                        title={t("Standard Pages")}
                        description={t(
                            "Manage your website's standard pages",
                        )}
                    />

                    <DataTable
                    t={t}
                        columns={staticColumns}
                        data={pages.data}
                        pageIndex={pages.current_page - 1}
                        pageSize={pages.per_page}
                        totalCount={pages.total}
                        initialSearch={
                            route().params.search ?? ""
                        }
                        onPageChange={(page) =>
                            navigate({
                                page: page + 1,
                                per_page: pages.per_page,
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
                                per_page: pages.per_page,
                                search,
                            })
                        }
                    />
                </div>

              

                <div className="space-y-3">
                    <div className="flex items-center justify-between">
                        <Heading
                        t={t}
                            title={t("Custom Pages")}
                            description={t(
                                "Create and manage custom website pages",
                            )}
                        />

                        <Button
                            onClick={() =>
                                router.get(
                                    route("dashboard.admin.add.page"),
                                )
                            }
                        >
                            <Plus className="size-4" />
                            {t("Add New Page")}
                        </Button>
                    </div>

                    <DataTable
                    t={t}
                        columns={customColumns}
                        data={custom_pages.data}
                        pageIndex={
                            custom_pages.current_page - 1
                        }
                        pageSize={custom_pages.per_page}
                        totalCount={custom_pages.total}
                        initialSearch={
                            route().params.custom_search ?? ""
                        }
                        onPageChange={(page) =>
                            navigate({
                                custom_page: page + 1,
                                custom_per_page:
                                    custom_pages.per_page,
                                custom_search:
                                    route().params.custom_search,
                            })
                        }
                        onPageSizeChange={(size) =>
                            navigate({
                                custom_page: 1,
                                custom_per_page: size,
                                custom_search:
                                    route().params.custom_search,
                            })
                        }
                        onSearch={(search) =>
                            navigate({
                                custom_page: 1,
                                custom_per_page:
                                    custom_pages.per_page,
                                custom_search: search,
                            })
                        }
                    />
                </div>

              

                <ConfirmDialog
                    open={statusDialogOpen}
                    onOpenChange={setStatusDialogOpen}
                    title={t("Are you sure?")}
                    // icon={
                    //     <Power className="size-6" />
                    // }
                    description={t(
                        "If you proceed, you will enable/disable this page.",
                    )}
                    confirmLabel={t("Yes, proceed")}
                    cancelLabel={t("Cancel")}
                    onConfirm={handleStatus}
                    loading={actionLoading}
                />

               
                <ConfirmDialog
                    open={deleteDialogOpen}
                    onOpenChange={setDeleteDialogOpen}
                    title={t("Delete Page")}
                    icon={
                        <Trash2 className="size-6" />
                    }
                    description={
                        pageToDelete
                            ? t(
                                  `Are you sure you want to delete ${pageToDelete.name}? This action cannot be undone.`,
                              )
                            : t(
                                  "Are you sure you want to delete this page?",
                              )
                    }
                    confirmLabel={t("Delete")}
                    cancelLabel={t("Cancel")}
                    onConfirm={handleDelete}
                    loading={actionLoading}
                />
            </div>
        </AppLayout>
    );
}