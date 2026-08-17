import { CustomBadge } from "@/components/table/badge";
import { AuthenticationLog } from "@/types/admin";
import { ColumnDef } from "@tanstack/react-table";

export const getColumns = ({
    pageIndex,
    pageSize,
    t,
}: {
    pageIndex: number;
    pageSize: number;
    t: (key: string) => string;
}): ColumnDef<AuthenticationLog>[] => [
    {
        accessorKey: t("S.No"),
        header: t("S.No"),
        cell: ({ row }) => pageIndex * pageSize + row.index + 1,
    },

    {
        accessorKey: t("IP Address"),
        header: t("IP Address"),
        cell: ({ row }) => row.original.ip_address,
    },

    {
        accessorKey: t("Browser"),
        header: t("Browser"),
        cell: ({ row }) =>
            CustomBadge(
                `${row.original.platform ?? "-"} - ${row.original.browser ?? "-"}`,
                "bg-green-500 text-white dark:bg-green-800",
            ),
    },

    {
        accessorKey: t("Login At"),
        header: t("Login At"),
        cell: ({ row }) => {
            if (!row.original.login_at) {
                return "-";
            }

            return new Date(row.original.login_at).toLocaleString(
                undefined,
                {
                    day: "2-digit",
                    month: "short",
                    year: "numeric",
                    hour: "2-digit",
                    minute: "2-digit",
                },
            );
        },
    },

    {
        accessorKey: t("Login Successful"),
        header: t("Login Successful"),
        cell: ({ row }) =>
            row.original.login_successful === 1
                ? CustomBadge(
                      t("YES"),
                      "bg-green-500 text-white dark:bg-green-800",
                  )
                : CustomBadge(
                      t("NO"),
                      "bg-red-500 text-white dark:bg-red-800",
                  ),
    },

    {
        accessorKey: t("Logout At"),
        header: t("Logout At"),
        cell: ({ row }) => {
            if (!row.original.logout_at) {
                return "-";
            }

            return new Date(row.original.logout_at).toLocaleString(
                undefined,
                {
                    day: "2-digit",
                    month: "short",
                    year: "numeric",
                    hour: "2-digit",
                    minute: "2-digit",
                },
            );
        },
    },

    // {
    //     accessorKey: t("Cleared by user"),
    //     header: t("Cleared by user"),
    //     cell: ({ row }) =>
    //         row.original.cleared_by_user === 1
    //             ? CustomBadge(
    //                   t("YES"),
    //                   "bg-green-500 text-white dark:bg-green-800",
    //               )
    //             : CustomBadge(
    //                   t("NO"),
    //                   "bg-red-500 text-white dark:bg-red-800",
    //               ),
    // },
];