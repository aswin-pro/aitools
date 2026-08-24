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
import { BlogCategory } from "@/types/admin";

export const getColumns = ({
    pageIndex,
    t,
    onEdit,
    onAction,
}: {
    pageIndex: number;
    t: (key: string) => string;
    onEdit: (category: BlogCategory) => void;
    onAction: (
        category: BlogCategory,
        action: "publish" | "unpublish" | "delete",
    ) => void;
}): ColumnDef<BlogCategory>[] => [
    {
        accessorKey: t("S.No"),
        header: t("S.No"),
        cell: ({ row }) => pageIndex * 10 + row.index + 1,
    },

    {
        accessorKey: "created_at",
        header: t("Date"),
        cell: ({ row }) => row.original.formatted_created_at
    },

    {
        accessorKey: t("Name"),
        header: t("Name"),
        cell: ({ row }) => row.original.blog_category_title,
    },

    {
        accessorKey: t("Status"),
        header: t("Status"),
        cell: ({ row }) =>
            CustomBadge(
                row.original.status === 1 ? t("Published") : t("Unpublished"),
                row.original.status === 1
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

                        {category.status === 0 ? (
                            <DropdownMenuItem
                                onClick={() => onAction(category, "publish")}
                            >
                                <CheckCircle className="mr-2 size-4" />
                                {t("Publish")}
                            </DropdownMenuItem>
                        ) : (
                            <DropdownMenuItem
                                onClick={() => onAction(category, "unpublish")}
                            >
                                <XCircle className="mr-2 size-4" />
                                {t("Unpublish")}
                            </DropdownMenuItem>
                        )}

                        <DropdownMenuItem
                            className="text-destructive focus:text-destructive"
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
