import { CustomBadge } from "@/components/table/badge";
import { Button } from "@/components/ui/button";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { User } from "@/types/admin";
import { ColumnDef } from "@tanstack/react-table";
import {
    MoreVertical,
    Pencil,
    RefreshCw,
    Trash2,
    UserCheck,
    UserX,
} from "lucide-react";

export const getColumns = ({
    pageIndex,
    pageSize,
    t,
    onEdit,
    onAction,
    onChangePlan,
}: {
    pageIndex: number;
    pageSize: number;
    t: (key: string) => string;
    onEdit: (user: User) => void;
    onAction: (
        user: User,
        action: "activate" | "deactivate" | "delete",
    ) => void;
    onChangePlan: (user: User) => void;
}): ColumnDef<User>[] => [
    {
        accessorKey: t("S.No"),
        header: t("S.No"),
        cell: ({ row }) => pageIndex * 10 + row.index + 1,
    },

    {
        accessorKey: t("Name"),
        header: t("Name"),
        cell: ({ row }) => (
            <a
                href={route("dashboard.admin.view.user", row.original.id)}
                className="hover:underline"
            >
                {row.original.name ?? t("User not found")}
            </a>
        ),
    },

    {
        accessorKey: t("Email"),
        header: t("Email"),
        cell: ({ row }) => row.original.email,
    },

    {
        accessorKey: t("Current Plan"),
        header: t("Current Plan"),
        cell: ({ row }) => row.original.plan?.name ?? "-",
    },
    {
        accessorKey: "status",
        header: t("Status"),

        cell: ({ row }) =>
            CustomBadge(
                row.original.status === 1 ? t("Active") : t("Deactivated"),

                row.original.status === 1
                    ? "bg-green-500 text-white dark:bg-green-800"
                    : "bg-red-500 text-white dark:bg-red-800",
            ),
    },

    {
        id: "Actions",
        header: t("Actions"),

        cell: ({ row }) => {
            const user = row.original;

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
                        <DropdownMenuItem onClick={() => onEdit(user)}>
                            <Pencil className="mr-2 size-4" />
                            {t("Edit")}
                        </DropdownMenuItem>

                        <DropdownMenuItem onClick={() => onChangePlan(user)}>
                            <RefreshCw className="mr-2 size-4" />
                            {t("Change Plan")}
                        </DropdownMenuItem>

                        {user.status === 0 ? (
                            <DropdownMenuItem
                                onClick={() => onAction(user, "activate")}
                            >
                                <UserCheck className="mr-2 size-4" />
                                {t("Activate")}
                            </DropdownMenuItem>
                        ) : (
                            <DropdownMenuItem
                                onClick={() => onAction(user, "deactivate")}
                            >
                                <UserX className="mr-2 size-4" />
                                {t("Deactivate")}
                            </DropdownMenuItem>
                        )}

                        {/* Delete */}
                        <DropdownMenuItem
                            className="text-destructive focus:text-destructive"
                            onClick={() => onAction(user, "delete")}
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
