import Heading from "@/components/heading";
import { DataTable } from "@/components/table/data-table";
import { Button } from "@/components/ui/button";

import { BreadcrumbItem, LaravelPagination, NavigateParams } from "@/types";
import { CustomTemplate } from "@/types/admin";
import { Head, router } from "@inertiajs/react";
import { CheckCircle, Plus, XCircle } from "lucide-react";
import { useMemo, useState } from "react";
import { useTranslation } from "react-i18next";
import { getColumns } from "./columns";
import { toast } from "sonner";
import AppLayout from "@/layouts/app/app-layout";
import { ConfirmDialog } from "@/components/admin/confirm-dialog";

interface TemplatesProps {
    templates: LaravelPagination<CustomTemplate>;
    filters?: {
        search?: string;
        per_page?: number;
    };
}

type TemplateAction = "active" | "inactive";



export default function Index({ templates }: TemplatesProps) {
    const { t } = useTranslation();

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: t("Dashboard"),
            href: route("dashboard.admin.overview"),
        },
        {
            title: t("Content Templates"),
            href: "#",
        },
    ];

    const [confirmOpen, setConfirmOpen] = useState(false);
    const [selectedTemplate, setSelectedTemplate] =
        useState<CustomTemplate | null>(null);
    const [selectedAction, setSelectedAction] = useState<TemplateAction | null>(
        null,
    );
    const [actionLoading, setActionLoading] = useState(false);

    const navigate = (params: NavigateParams) => {
        router.reload({
            only: ["templates"],
            data: params,
        });
    };

    const handleEdit = (template: CustomTemplate) => {
        router.get(route("dashboard.admin.edit.template", template.id));
    };

    const handleAction = (template: CustomTemplate, action: TemplateAction) => {
        setSelectedTemplate(template);
        setSelectedAction(action);
        setConfirmOpen(true);
    };

    const handleConfirmAction = () => {
        if (!selectedTemplate || !selectedAction) {
            return;
        }

        setActionLoading(true);

        router.get(
            route("dashboard.admin.delete.template"),
            {
                id: selectedTemplate.id,
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success(t("Template Status Updated Successfully!"));

                    setConfirmOpen(false);
                    setSelectedTemplate(null);
                    setSelectedAction(null);
                },
                onError: (errors) => {
                    toast.error(errors.action ?? t("Something went wrong."));

                    setConfirmOpen(false);
                    setSelectedTemplate(null);
                    setSelectedAction(null);
                },
                onFinish: () => {
                    setActionLoading(false);
                },
            },
        );
    };

const dialogContent = {
    active: {
        icon: <CheckCircle className="size-7 text-green-600" />,
        title: t("Active template?"),
        description: t("If you proceed, this template will be active."),
        confirmLabel: t("Yes, active"),
    },

    inactive: {
        icon: <XCircle className="size-7 text-destructive" />,
        title: t("Inactive template?"),
        description: t("If you proceed, this template will be inactive."),
        confirmLabel: t("Yes, inactive"),
    },
};

    const currentDialog = selectedAction ? dialogContent[selectedAction] : null;

    const columns = useMemo(
        () =>
            getColumns({
                pageIndex: templates.current_page - 1,
                pageSize: templates.per_page,
                t,
                onEdit: handleEdit,
                onAction: handleAction,
            }),
        [templates.current_page, templates.per_page, t],
    );

    const actionText =
        selectedAction === "active" ? t("activate") : t("deactivate");

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Templates")} />

            <div className="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <Heading
                        title={t("Templates")}
                        description={t("Manage your templates")}
                    />

                    <Button
                        onClick={() =>
                            router.get(route("dashboard.admin.add.template"))
                        }
                    >
                        <Plus className=" size-4" />
                        {t("Create Template")}
                    </Button>
                </div>

                <DataTable
                    columns={columns}
                    data={templates.data}
                    pageIndex={templates.current_page - 1}
                    pageSize={templates.per_page}
                    totalCount={templates.total}
                    initialSearch={route().params.search ?? ""}
                    onPageChange={(page) =>
                        navigate({
                            page: page + 1,
                            per_page: templates.per_page,
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

         <ConfirmDialog
    open={confirmOpen}
    onOpenChange={(open) => {
        if (actionLoading) {
            return;
        }

        setConfirmOpen(open);

        if (!open) {
            setSelectedTemplate(null);
            setSelectedAction(null);
        }
    }}
    icon={currentDialog?.icon}
    title={currentDialog?.title ?? ""}
    description={currentDialog?.description ?? ""}
    confirmLabel={currentDialog?.confirmLabel ?? ""}
    cancelLabel={t("Cancel")}
    onConfirm={handleConfirmAction}
    loading={actionLoading}
/>
            </div>
        </AppLayout>
    );
}
