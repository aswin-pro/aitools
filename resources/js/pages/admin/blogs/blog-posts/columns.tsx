import { ColumnDef } from "@tanstack/react-table";
import { useTranslation } from "react-i18next";
import {
    MoreHorizontal,
    Pencil,
    CheckCircle,
    XCircle,
    Trash2,
    MoreVertical,
} from "lucide-react";
import { Blog } from "@/types/admin";
import { Button } from "@/components/ui/button";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { CustomBadge } from "@/components/table/badge";

interface GetColumnsProps {
    t: ReturnType<typeof useTranslation>["t"];
    onEdit: (blog: Blog) => void;
    onAction: (blog: Blog, action: "publish" | "unpublish" | "delete") => void;
}

export const getColumns = ({
    t,
    onEdit,
    onAction,
}: GetColumnsProps): ColumnDef<Blog>[] => [
    {
        accessorKey: "S_No",
        header: "S.No",
        cell: ({ row }) => row.index + 1,
    },

    {
        accessorKey: "formatted_created_at",
        header: t("Date"),
        cell: ({ row }) => row.original.formatted_created_at,
    },

    {
        accessorKey: "blog_category",
        header: t("Category"),
        cell: ({ row }) =>
            row.original.blog_category?.blog_category_title ?? "-",
    },

    // {
    //     accessorKey: "tags",
    //     header: t("Tags"),
    //     cell: ({ row }) => {
    //         const tags = row.original.tags
    //             ?.split(",")
    //             .map((tag) => tag.trim())
    //             .filter(Boolean)
    //             .slice(0, 2);

    //         return (
    //             <div className="flex flex-wrap gap-1">
    //                 {tags?.map((tag) => (
    //                     <span
    //                         key={tag}
    //                         className="rounded-md bg-primary px-2 py-1 text-xs text-white"
    //                     >
    //                         {tag}
    //                     </span>
    //                 ))}
    //             </div>
    //         );
    //     },
    // },

    {
        accessorKey: "heading",
        header: t("Title"),
    },

    {
        accessorKey: "short_description",
        header: t("Short description"),
        cell: ({ row }) => {
            const description = row.original.short_description ?? "";

            return description.length > 99
                ? `${description.substring(0, 99)}...`
                : description;
        },
    },

    {
        accessorKey: "status",

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
    accessorKey: "actions",
    header: t("Actions"),

    cell: ({ row }) => {
        const blog = row.original;

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
                    {/* Edit */}
                    <DropdownMenuItem onClick={() => onEdit(blog)}>
                        <Pencil className="mr-2 size-4" />
                        {t("Edit")}
                    </DropdownMenuItem>

                    {/* Publish / Unpublish */}
                    {blog.status === 0 ? (
                        <DropdownMenuItem
                            onClick={() => onAction(blog, "publish")}
                        >
                            <CheckCircle className="mr-2 size-4" />
                            {t("Publish")}
                        </DropdownMenuItem>
                    ) : (
                        <DropdownMenuItem
                            onClick={() => onAction(blog, "unpublish")}
                        >
                            <XCircle className="mr-2 size-4" />
                            {t("Unpublish")}
                        </DropdownMenuItem>
                    )}

                    {/* Delete */}
                    <DropdownMenuItem
                        className="text-destructive focus:text-destructive"
                        onClick={() => onAction(blog, "delete")}
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
