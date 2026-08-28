import { ColumnDef } from "@tanstack/react-table";
import { useTranslation } from "react-i18next";

import { ChatGenius } from "@/types/admin";
import { CustomBadge } from "@/components/table/badge";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Button } from "@/components/ui/button";
import {
    MoreVertical,
    Pencil,
    RefreshCw,
    Trash2,
    UserCheck,
    UserX,
} from "lucide-react";

interface GetColumnsProps {
    pageIndex: number;
    pageSize: number;

    t: ReturnType<typeof useTranslation>["t"];

    onEdit: (chatgenius: ChatGenius) => void;

    onAction: (
        chatgenius: ChatGenius,
        action: "activate" | "deactivate" | "delete",
    ) => void;
}

export const getColumns = ({
    pageIndex,
    pageSize,
    t,
    onEdit,
    onAction,
}: GetColumnsProps): ColumnDef<ChatGenius>[] => [
    {
        accessorKey: "S_No",
        header: t("S.No"),
        cell: ({ row }) => pageIndex * pageSize + row.index + 1,
    },

    {
        accessorKey: "chat_genius_name",
        header: t("Chat Assistant"),
        cell: ({ row }) => {
            const chatgenius = row.original;

            return (
                <div className="flex items-center gap-3">
                    <img
                        src={`/${chatgenius.chat_genius_image}`}
                        alt={chatgenius.chat_genius_name}
                        className="size-10 rounded-full object-cover"
                    />

                    <span className="font-medium">
                        {chatgenius.chat_genius_name}
                    </span>
                </div>
            );
        },
    },

    {
        accessorKey: "chat_genius_expert",
        header: t("Expert"),
        cell: ({ row }) => row.original.chat_genius_expert ?? "-",
    },

    {
        accessorKey: "chat_genius_description",
        header: t("Description"),
        cell: ({ row }) => {
            const description = row.original.chat_genius_description ?? "";

            return description.length > 80
                ? `${description.substring(0, 80)}...`
                : description;
        },
    },

    // {
    //     accessorKey: "chat_genius_message",
    //     header: t("Message"),
    //     cell: ({ row }) => {
    //         const message =
    //             row.original.chat_genius_message ?? "";

    //         return message.length > 80
    //             ? `${message.substring(0, 80)}...`
    //             : message;
    //     },
    // },

    {
        accessorKey: "status",
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
            const chatgenius = row.original;

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
                        <DropdownMenuItem onClick={() => onEdit(chatgenius)}>
                            <Pencil className="mr-2 size-4" />
                            {t("Edit")}
                        </DropdownMenuItem>

                        {chatgenius.status === 0 ? (
                            <DropdownMenuItem
                                onClick={() => onAction(chatgenius, "activate")}
                            >
                                <UserCheck className="mr-2 size-4" />
                                {t("Activate")}
                            </DropdownMenuItem>
                        ) : (
                            <DropdownMenuItem
                                onClick={() =>
                                    onAction(chatgenius, "deactivate")
                                }
                            >
                                <UserX className="mr-2 size-4" />
                                {t("Deactivate")}
                            </DropdownMenuItem>
                        )}

                        <DropdownMenuItem
                            className="text-destructive focus:text-destructive"
                            onClick={() => onAction(chatgenius, "delete")}
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
