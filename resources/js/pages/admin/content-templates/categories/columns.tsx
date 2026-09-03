import { CustomBadge } from "@/components/table/badge";
import { Button } from "@/components/ui/button";

import { ColumnDef } from "@tanstack/react-table";
import {
    CheckCircle,
    MoreVertical,
    Pencil,
    Trash2,
    XCircle,
} from "lucide-react";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { CustomTemplateCategory } from "@/types/admin";

export const getColumns = ({
    pageIndex,
    pageSize,
    t,
    onEdit,
    onAction,
}: {
    pageIndex: number;
    pageSize: number;
    t: (key: string) => string;
    onEdit: (category: CustomTemplateCategory) => void;
    onAction: (
        category: CustomTemplateCategory,
        action: "active" | "inactive" | "delete",
    ) => void;
}): ColumnDef<CustomTemplateCategory>[] => [
    {
        accessorKey: t("S.No"),
        header: t("S.No"),
        cell: ({ row }) => pageIndex * pageSize + row.index + 1,

    },

    {
        accessorKey: "Category Name",
        header: t("Category Name"),
        cell: ({ row }) => row.original.category_name,
    },

    {
        accessorKey: t("Status"),
        header: t("Status"),
        cell: ({ row }) =>
            CustomBadge(
                row.original.status ? t("Active") : t("Inactive"),
                row.original.status
                    ? "bg-green-500 text-white dark:bg-green-800"
                    : "bg-red-500 text-white dark:bg-red-800",
            ),
    },

    {
        accessorKey: t("Actions"),
        header: t("Actions"),
        cell: ({ row }) => {
            const category = row.original;

            return (
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button
                            variant="ghost"
                            size="icon"
                            className="size-8 cursor-pointer border shadow-sm outline-none hover:bg-transparent focus-visible:ring-0 focus-visible:ring-offset-0"
                        >
                            <MoreVertical className="size-4" />
                        </Button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent align="end">
                        <DropdownMenuItem onClick={() => onEdit(category)}>
                            <Pencil className="mr-2 size-4" />
                            {t("Edit")}
                        </DropdownMenuItem>

                        {category.status ? (
                            <DropdownMenuItem
                                onClick={() => onAction(category, "inactive")}
                            >
                                <XCircle className="mr-2 size-4" />
                                {t("Inactive")}
                            </DropdownMenuItem>
                        ) : (
                            <DropdownMenuItem
                                onClick={() => onAction(category, "active")}
                            >
                                <CheckCircle className="mr-2 size-4" />
                                {t("Active")}
                            </DropdownMenuItem>
                        )}

                        <DropdownMenuItem
                            onClick={() => onAction(category, "delete")}
                        >
                            <Trash2 className="mr-2 size-4" />
                            {t("Delete")}
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            );
        },
    },
];
