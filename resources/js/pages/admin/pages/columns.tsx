import { CustomBadge } from "@/components/table/badge";
import { Button } from "@/components/ui/button";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { ColumnDef } from "@tanstack/react-table";
import { Eye, EyeOff, MoreVertical, Pencil, Power, Trash2 } from "lucide-react";

export type PageItem = {
    id: number;
    name: string;
    slug: string;
    status: number;
};

export const getColumns = ({
    pageIndex,
    pageSize,
    t,
    isCustom,
    onEdit,
    onStatus,
    onDelete,
}: {
    pageIndex: number;
    pageSize: number;
    t: (key: string) => string;
    isCustom: boolean;
    onEdit: (page: PageItem) => void;
    onStatus: (page: PageItem) => void;
    onDelete?: (page: PageItem) => void;
}): ColumnDef<PageItem>[] => [
    {
        accessorKey: "S_No",
        header: t("S.No"),
        cell: ({ row }) => pageIndex * pageSize + row.index + 1,
    },

    {
        accessorKey: "Name",
        header: t("Page"),
        cell: ({ row }) => (
            <span className="capitalize">{row.original.name}</span>
        ),
    },

    {
        accessorKey: "Slug",
        header: t("Slug"),
        cell: ({ row }) => {
            const page = row.original;

            const url = isCustom
                ? page.slug === "home"
                    ? "/"
                    : `/p/${page.slug}`
                : page.slug === "home" ||
                    page.slug === "hero" ||
                    page.slug === "footer"
                  ? "/"
                  : `/${page.slug}`;

            return (
                <a
                    href={url}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="text-primary hover:underline"
                >
                    {page.slug === "/" ? "/" : `/${page.slug}`}
                </a>
            );
        },
    },

    {
        accessorKey: "Status",
        header: t("Status"),
        cell: ({ row }) =>
            CustomBadge(
                row.original.status === 1 ? t("Enabled") : t("Disabled"),
                row.original.status === 1
                    ? "bg-green-500 text-white dark:bg-green-800"
                    : "bg-red-500 text-white dark:bg-red-800",
            ),
    },

    {
        id: "Actions",
        header: t("Actions"),
        cell: ({ row }) => {
            const page = row.original;

            const cannotDisable =
                !isCustom &&
                page.status === 1 &&
                [
                    "home",
                    "hero",
                    "newsletter",
                    "pricing-home",
                    "social-links",
                ].includes(page.slug);

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
                        <DropdownMenuItem onClick={() => onEdit(page)}>
                            <Pencil className="mr-2 size-4" />
                            {t("Edit")}
                        </DropdownMenuItem>

                        {!cannotDisable && (
                            <DropdownMenuItem onClick={() => onStatus(page)}>
                                {page.status === 1 ? (
                                    <>
                                        <EyeOff className="mr-2 size-4" />
                                        {t("Disable")}
                                    </>
                                ) : (
                                    <>
                                        <Eye className="mr-2 size-4" />
                                        {t("Enable")}
                                    </>
                                )}
                            </DropdownMenuItem>
                        )}

                        {isCustom && onDelete && (
                            <DropdownMenuItem
                                className="text-destructive focus:text-destructive"
                                onClick={() => onDelete(page)}
                            >
                                <Trash2 className="mr-2 size-4" />
                                {t("Delete")}
                            </DropdownMenuItem>
                        )}
                    </DropdownMenuContent>
                </DropdownMenu>
            );
        },
    },
];
