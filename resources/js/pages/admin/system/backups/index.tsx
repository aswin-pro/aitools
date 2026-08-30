import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import { BreadcrumbItem } from "@/types";
import { Head, router } from "@inertiajs/react";
import { useState } from "react";
import { useTranslation } from "react-i18next";
import { getColumns } from "./columns";
import { DataTable } from "@/components/table/data-table";
import { Button } from "@/components/ui/button";
import { LoadingSwap } from "@/components/ui/loading-swap";
import { toast } from "sonner";
import { ConfirmDialog } from "@/components/admin/confirm-dialog";
import { Trash2 } from "lucide-react";

interface Backup {
    id: number;
    backup_id: string;
    version: string;
    status: number;
    type: string;
    file_name?: string;
    path?: string;
        created_at: string;

}

interface BackupPagination {
    data: Backup[];
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
}

interface IndexProps {
    fileBackups: BackupPagination;
    databaseBackups: BackupPagination;

    filters: {
        file_search?: string;
        database_search?: string;
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Backups",
        href: "#",
    },
];

export default function Index({
    fileBackups,
    databaseBackups,
    filters,
}: IndexProps) {
    const { t } = useTranslation();

    const [fileBackupLoading, setFileBackupLoading] = useState(false);
    const [databaseBackupLoading, setDatabaseBackupLoading] = useState(false);

const [deleteBackup, setDeleteBackup] = useState<Backup | null>(null);
const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
const [deleteLoading, setDeleteLoading] = useState(false);

const openDeleteDialog = (backup: Backup) => {
    setDeleteBackup(backup);
    setDeleteDialogOpen(true);
};

const fileColumns = getColumns({
    t,
    onDelete: openDeleteDialog,
});

const databaseColumns = getColumns({
    t,
    onDelete: openDeleteDialog,
});

    const navigate = (params: Record<string, any>) => {
        router.get(route("dashboard.admin.system.backups"), params, {
            preserveScroll: true,
            preserveState: true,
        });
    };

    const createFileBackup = () => {
        setFileBackupLoading(true);

        router.get(
            route("dashboard.admin.create.file.backup"),
            {},
            {
                preserveScroll: true,

                onSuccess: () => {
                    toast.success(
                        t("File backup created successfully!"),
                    );
                },

                onError: () => {
                    toast.error(
                        t("Unable to create file backup."),
                    );
                },

                onFinish: () => {
                    setFileBackupLoading(false);
                },
            },
        );
    };

    const createDatabaseBackup = () => {
        setDatabaseBackupLoading(true);

        router.get(
            route("dashboard.admin.create.database.backup"),
            {},
            {
                preserveScroll: true,

                onSuccess: () => {
                    toast.success(
                        t("Database backup created successfully!"),
                    );
                },

                onError: () => {
                    toast.error(
                        t("Unable to create database backup."),
                    );
                },

                onFinish: () => {
                    setDatabaseBackupLoading(false);
                },
            },
        );
    };

    const handleDelete = () => {
    if (!deleteBackup) {
        return;
    }

    setDeleteLoading(true);

    router.get(
        route("dashboard.admin.backup.delete"),
        {
            id: deleteBackup.backup_id,
        },
        {
            preserveScroll: true,

            onSuccess: () => {
                toast.success(t("Backup deleted successfully!"));

                setDeleteDialogOpen(false);
                setDeleteBackup(null);
            },

            onError: () => {
                toast.error(t("Failed to delete backup."));
            },

            onFinish: () => {
                setDeleteLoading(false);
            },
        },
    );
};



    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Backups")} />

            <Heading
                title={t("Backups")}
                description={t(
                    "Create, download, restore, and manage your system backups",
                )}
            />

            {/* File Backups */}
            <div className="mt-8">
                <div className="mb-4 flex items-center justify-between">
                    <div>
                        <h2 className="font-semibold">
                            {t("File Backups")}
                        </h2>

                        <p className="text-sm text-muted-foreground">
                            {t(
                                "Manage your application file backups.",
                            )}
                        </p>
                    </div>

                    <Button
                        type="button"
                        onClick={createFileBackup}
                        disabled={fileBackupLoading || databaseBackupLoading}
                    >
                        <LoadingSwap isLoading={fileBackupLoading}>
                            {t("Create Backup")}
                        </LoadingSwap>
                    </Button>
                </div>

                <DataTable
                    columns={fileColumns}
                    data={fileBackups.data}
                    pageIndex={fileBackups.current_page - 1}
                    pageSize={fileBackups.per_page}
                    totalCount={fileBackups.total}
                    onPageChange={(page) =>
                        navigate({
                            file_page: page + 1,
                            file_per_page: fileBackups.per_page,
                            file_search: filters.file_search,
                        })
                    }
                    onPageSizeChange={(size) =>
                        navigate({
                            file_page: 1,
                            file_per_page: size,
                            file_search: filters.file_search,
                        })
                    }
                    onSearch={(search) =>
                        navigate({
                            file_page: 1,
                            file_per_page: fileBackups.per_page,
                            file_search: search,
                        })
                    }
                />
            </div>

            {/* Database Backups */}
            <div className="mt-10">
                <div className="mb-4 flex items-center justify-between">
                    <div>
                        <h2 className="font-semibold">
                            {t("Database Backups")}
                        </h2>

                        <p className="text-sm text-muted-foreground">
                            {t(
                                "Manage your database backups.",
                            )}
                        </p>
                    </div>

                    <Button
                        type="button"
                        onClick={createDatabaseBackup}
                        disabled={fileBackupLoading || databaseBackupLoading}
                    >
                        <LoadingSwap isLoading={databaseBackupLoading}>
                            {t("Create Backup")}
                        </LoadingSwap>
                    </Button>
                </div>

                <DataTable
                    columns={databaseColumns}
                    data={databaseBackups.data}
                    pageIndex={databaseBackups.current_page - 1}
                    pageSize={databaseBackups.per_page}
                    totalCount={databaseBackups.total}
                    onPageChange={(page) =>
                        navigate({
                            database_page: page + 1,
                            database_per_page:
                                databaseBackups.per_page,
                            database_search:
                                filters.database_search,
                        })
                    }
                    onPageSizeChange={(size) =>
                        navigate({
                            database_page: 1,
                            database_per_page: size,
                            database_search:
                                filters.database_search,
                        })
                    }
                    onSearch={(search) =>
                        navigate({
                            database_page: 1,
                            database_per_page:
                                databaseBackups.per_page,
                            database_search: search,
                        })
                    }
                />
            </div>

<ConfirmDialog
    open={deleteDialogOpen}
    onOpenChange={setDeleteDialogOpen}
    icon={<Trash2 className="size-7 text-destructive" />}
    title={t("Delete Backup?")}
    description={t(
        "Are you sure you want to delete this backup? This action cannot be undone.",
    )}
    confirmLabel={t("Yes, delete")}
    cancelLabel={t("Cancel")}
    onConfirm={handleDelete}
    loading={deleteLoading}
/>
        </AppLayout>
    );
}