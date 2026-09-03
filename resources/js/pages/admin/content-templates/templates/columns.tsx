import { CustomBadge } from "@/components/table/badge";
import { Button } from "@/components/ui/button";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { CustomTemplate } from "@/types/admin";
import { ColumnDef } from "@tanstack/react-table";
import {
    CheckCircle,
    MoreVertical,
    Pencil,
    XCircle,
} from "lucide-react";

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
    onEdit: (template: CustomTemplate) => void;
    onAction: (
        template: CustomTemplate,
        action: "active" | "inactive",
    ) => void;
}): ColumnDef<CustomTemplate>[] => [
    {
        accessorKey: "S_No",
        header: t("#"),
        cell: ({ row }) => pageIndex * pageSize + row.index + 1,
    },
    {
        accessorKey: "Category Name",
        header: t("Category"),
        cell: ({ row }) => row.original.category_name,
    },
    {
        accessorKey: "Name",
        header: t("Name"),
        cell: ({ row }) => row.original.name,
    },
    {
        accessorKey: "Description",
        header: t("Description"),
        cell: ({ row }) => row.original.description,
    },
{
    accessorKey: "Updated_at",
    header: t("Last Updated on"),
    cell: ({ row }) =>
        row.original.formatted_updated_at ?? "-",
},
    {
        accessorKey: "Status",
        header: t("Status"),
        cell: ({ row }) =>
            CustomBadge(
                row.original.status
                    ? t("Activated")
                    : t("Deactivated"),
                row.original.status
                    ? "bg-green-500 text-white dark:bg-green-800"
                    : "bg-red-500 text-white dark:bg-red-800",
            ),
    },
    {
        accessorKey: "Actions",
        header: t("Actions"),
        cell: ({ row }) => {
            const template = row.original;

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
                        <DropdownMenuItem
                            onClick={() => onEdit(template)}
                        >
                            <Pencil className="mr-2 size-4" />
                            {t("Edit")}
                        </DropdownMenuItem>

                        {template.status ? (
                            <DropdownMenuItem
                                onClick={() =>
                                    onAction(template, "inactive")
                                }
                            >
                                <XCircle className="mr-2 size-4" />
                                {t("Deactivate")}
                            </DropdownMenuItem>
                        ) : (
                            <DropdownMenuItem
                                onClick={() =>
                                    onAction(template, "active")
                                }
                            >
                                <CheckCircle className="mr-2 size-4" />
                                {t("Activate")}
                            </DropdownMenuItem>
                        )}
                    </DropdownMenuContent>
                </DropdownMenu>
            );
        },
    },
];