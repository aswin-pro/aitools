import { Button } from "@headlessui/react";
import { ColumnDef } from "@tanstack/react-table";
import { TFunction } from "i18next";
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

interface GetColumnsProps {
    t: TFunction;
    onDelete: (backup: Backup) => void;
}

export function getColumns({ t, onDelete }: GetColumnsProps): ColumnDef<Backup>[] {
    return [
        {
            id: "S_No",
            header: t("S.No"),
            cell: ({ row }) => row.index + 1,
        },
{
    accessorKey: "Created_at",
    header: t("Created At"),
    cell: ({ row }) =>
        new Date(row.original.created_at).toLocaleString("en-IN", {
            day: "2-digit",
            month: "short",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        }),
},

        {
            accessorKey: "Version",
            header: t("Version"),
            cell: ({ row }) => row.original.version,
        },

        {
            accessorKey: "Status",
            header: t("Status"),
            cell: ({ row }) =>
                row.original.status === 1 ? t("Backuped") : t("Not Backuped"),
        },

{
    id: "Action",
    header: t("Action"),
    cell: ({ row }) => (
        <Button
            type="button"
            className="flex items-center gap-2 text-destructive hover:text-destructive"
            onClick={() => onDelete(row.original)}
        >
            <Trash2 className="size-4" />
            <span>Delete</span>
        </Button>
    ),
},
    ];
}
