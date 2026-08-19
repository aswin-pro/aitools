import { CustomBadge } from "@/components/table/badge";
import { Button } from "@/components/ui/button";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { AuthenticationLog, Currencies } from "@/types/admin";
import { ColumnDef } from "@tanstack/react-table";
import { MoreHorizontal, MoreVertical, Pencil, Trash2 } from "lucide-react";

export const getColumns = ({
    pageIndex,
    pageSize,
    t,
    onEdit
    onDelete
}: {
    pageIndex: number;
    pageSize: number;
    t: (key: string) => string;
    onEdit: (currency: Currency) => void;
}): ColumnDef<Currencies>[] => [
    {
        accessorKey: t("S.No"),
        header: t("S.No"),
        cell: ({ row }) => pageIndex * pageSize + row.index + 1,
    },

    {
        accessorKey: t("Name"),
        header: t("Name"),
        cell: ({ row }) => row.original.name,
    },

    {
        accessorKey: t("ISO Code"),
        header: t("ISO Code"),
        cell: ({ row }) => row.original.iso_code,
    },

    {
        accessorKey: t("Currency sign"),
        header: t("Currencty Sign"),
        cell: ({ row }) => row.original.symbol,
    },

    {
        accessorKey: "Symbol first",
        header: t("Sign First"),
        cell: ({ row }) =>
            CustomBadge(
                row.original.symbol_first === "true" ? t("Yes") : t("No"),
                row.original.symbol_first === "true"
                    ? "bg-green-500 text-white dark:bg-green-800"
                    : "bg-red-500 text-white dark:bg-red-800",
            ),
    },

    {
        id: "Actions",
        header: t("Actions"),
        cell: ({ row }) => {
            const currency = row.original;

            return (
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button
                            variant="ghost"
                            size="icon"
                            className="size-8 cursor-pointer  border  shadow-sm outline-none hover:bg-transparent focus-visible:ring-0 focus-visible:ring-offset-0"
                        >
                            <MoreVertical className="size-4" />
                        </Button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent align="end">
                        <DropdownMenuItem
                            onClick={() => onEdit(currency)}
                        >
                            <Pencil className="mr-2 size-4" />
                            {t("Edit")}
                        </DropdownMenuItem>

                        <DropdownMenuItem
                            className="text-destructive focus:text-destructive"
                                onClick={() => onDelete(row.original.id)}
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
