import { ColumnDef } from "@tanstack/react-table";
import { TranslationLanguage } from "@/types/translation-manager";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Button } from "@/components/ui/button";
import { MoreVertical, Pencil, Search, Download, Trash2 } from "lucide-react";
import { router } from "@inertiajs/react";

export const getColumns = ({
    t,
    defaultLocale,
}: {
    t: (key: string) => string;
    defaultLocale?: string;
}): ColumnDef<TranslationLanguage>[] => [
    {
        accessorKey: "Name",
        header: t("Name"),
        cell: ({ row }) => row.original.name,
    },

    {
        accessorKey: "Code",
        header: t("Locale"),
        cell: ({ row }) => row.original.code,
    },

    {
        accessorKey: t("Actions"),
        header: t("Actions"),
        cell: ({ row }) => {
            const language = row.original;

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
                            onClick={() =>
                                router.get(
                                    route(
                                        "translation-manager.edit",
                                        language.code,
                                    ),
                                )
                            }
                        >
                            <Pencil className="mr-2 size-4" />
                            {t("Edit")}
                        </DropdownMenuItem>


<DropdownMenuItem
    onClick={() =>
        window.open(
            route("translation-manager.export", language.code),
            "_blank",
        )
    }
>
    <Download className="mr-2 size-4" />
    {t("Export")}
</DropdownMenuItem>

                        {language.code !== defaultLocale && (
                            <DropdownMenuItem
                                onClick={() => {
                                    // Delete will be wired next
                                }}
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
