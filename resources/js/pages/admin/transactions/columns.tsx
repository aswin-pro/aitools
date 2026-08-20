import { CustomBadge } from "@/components/table/badge";
import { Button } from "@/components/ui/button";
import { Transaction } from "@/types";
import { ColumnDef } from "@tanstack/react-table";
import {
    CheckCircle,
    Clock,
    MoreVertical,
    ScrollText,
    XCircle,
} from "lucide-react";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { useState } from "react";
import { ConfirmDialog } from "@/components/admin/confirm-dialog";

function TransactionActions({
    transaction,
    t,
}: {
    transaction: Transaction;
    t: (key: string) => string;
}) {
    const [confirmOpen, setConfirmOpen] = useState(false);

    const handleSuccess = () => {
        window.location.href = route("dashboard.admin.trans.status", {
            id: transaction.id,
            status: "SUCCESS",
        });
    };

    return (
        <>
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
                    {/* Invoice */}
                    {transaction.payment_status === "SUCCESS" &&
                        transaction.transaction_amount !== 0 && (
                            <DropdownMenuItem asChild>
                                <a
                                    href={route(
                                        "dashboard.admin.view.invoice",
                                        transaction.id,
                                    )}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    <ScrollText className="mr-2 size-4" />
                                    {t("Invoice")}
                                </a>
                            </DropdownMenuItem>
                        )}

                    {/* Pending */}
                    {transaction.payment_status !== "PENDING" && (
                        <DropdownMenuItem asChild>
                            <a
                                href={route(
                                    "dashboard.admin.trans.status",
                                    {
                                        id: transaction.id,
                                        status: "PENDING",
                                    },
                                )}
                            >
                                <Clock className="mr-2 size-4" />
                                {t("Pending")}
                            </a>
                        </DropdownMenuItem>
                    )}

                    {/* Success */}
                    {transaction.payment_status !== "SUCCESS" && (
                        <DropdownMenuItem
                            onSelect={(e) => {
                                e.preventDefault();
                                setConfirmOpen(true);
                            }}
                        >
                            <CheckCircle className="mr-2 size-4" />
                            {t("Success")}
                        </DropdownMenuItem>
                    )}

                    {/* Failed */}
                    {transaction.payment_status !== "FAILED" && (
                        <DropdownMenuItem asChild>
                            <a
                                href={route(
                                    "dashboard.admin.trans.status",
                                    {
                                        id: transaction.id,
                                        status: "FAILED",
                                    },
                                )}
                            >
                                <XCircle className="mr-2 size-4" />
                                {t("Failed")}
                            </a>
                        </DropdownMenuItem>
                    )}
                </DropdownMenuContent>
            </DropdownMenu>

            <ConfirmDialog
                open={confirmOpen}
                onOpenChange={setConfirmOpen}
                icon={
                    <CheckCircle className="size-7 text-green-600" />
                }
                title={t("Are you sure?")}
                description={t(
                    "If you proceed with this transaction, this payment will be marked as successful and the plan will be activated.",
                )}
                cancelLabel={t("Cancel")}
                confirmLabel={t("Yes, proceed")}
                onConfirm={handleSuccess}
            />
        </>
    );
}

export const getColumns = ({
    pageIndex,
    t,
}: {
    pageIndex: number;
    t: (key: string) => string;
}): ColumnDef<Transaction>[] => [
    {
        accessorKey: t("S.No"),
        header: t("S.No"),
        cell: ({ row }) => pageIndex * 10 + row.index + 1,
    },

    {
        accessorKey: t("Transaction Date"),
        header: t("Transaction Date"),
        cell: ({ row }) => row.original.formatted_created_at,
    },

    {
        accessorKey: t("Payment ID"),
        header: t("Payment ID"),
        cell: ({ row }) =>
            row.original.transaction_amount !== 0
                ? row.original.transaction_id
                : "-",
    },

    {
        accessorKey: t("Customer"),
        header: t("Customer"),
        cell: ({ row }) => (
            <a
                href={route(
                    "dashboard.admin.view.user",
                    row.original.user?.id,
                )}
                className="hover:underline"
            >
                {row.original.user?.name ?? "User not found"}
            </a>
        ),
    },

    {
        accessorKey: t("Plan"),
        header: t("Plan"),
        cell: ({ row }) => row.original.plan?.name ?? "-",
    },

    {
        accessorKey: t("Payment Mode"),
        header: t("Payment Mode"),
        cell: ({ row }) => row.original.payment_gateway_name,
    },

    {
        accessorKey: t("Amount"),
        header: t("Amount"),
        cell: ({ row }) =>
            row.original.currency?.symbol +
            " " +
            row.original.transaction_amount,
    },

    {
        accessorKey: t("Payment Status"),
        header: t("Payment Status"),
        cell: ({ row }) =>
            CustomBadge(
                {
                    PENDING: t("Pending"),
                    SUCCESS: t("Paid"),
                    FAILED: t("Failed"),
                    CANCELLED: t("Cancelled"),
                }[row.original.payment_status],
                {
                    PENDING:
                        "bg-orange-500 text-white dark:bg-orange-800",
                    SUCCESS:
                        "bg-green-500 text-white dark:bg-green-800",
                    FAILED:
                        "bg-red-500 text-white dark:bg-red-800",
                    CANCELLED:
                        "bg-red-500 text-white dark:bg-red-800",
                }[row.original.payment_status],
            ),
    },

    {
        accessorKey: t("Action"),
        header: t("Action"),
        cell: ({ row }) => (
            <TransactionActions
                transaction={row.original}
                t={t}
            />
        ),
    },
];