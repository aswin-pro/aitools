import { Button } from "@/components/ui/button";
import { CustomBadge } from "@/components/table/badge";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { assetUrl } from "@/helpers/asset-url";
import { MoreVertical, Pencil, Settings, UserCheck, UserX } from "lucide-react";
import { router } from "@inertiajs/react";
import { ColumnDef } from "@tanstack/react-table";
import { TFunction } from "i18next";

export interface PaymentMethod {
    id: number;
    name: string;
    display_name: string;
    logo: string;
    is_status: string;
    status: number;
    created_at?: string;
    updated_at?: string;
}

interface GetColumnsProps {
    pageIndex: number;
    pageSize: number;
    t: TFunction;
    onEdit: (paymentMethod: PaymentMethod) => void;
    onConfigure: (paymentMethod: PaymentMethod) => void;
    onAction: (paymentMethod: PaymentMethod) => void;
}

export const getColumns = ({
    pageIndex,
    pageSize,
    onEdit,
    onConfigure,
    t,
    onAction,
}: GetColumnsProps): ColumnDef<PaymentMethod>[] => [
    {
        accessorKey: "Payment Method",
        header: t("Payment Method"),
        cell: ({ row }) => {
            const paymentMethod = row.original;

            return (
                <div className="flex items-center py-1">
                    <div
                        className="mr-3 size-10 rounded-md bg-cover bg-center bg-no-repeat"
                        style={{
                            backgroundImage: `url(${assetUrl(
                                paymentMethod.logo,
                            )})`,
                        }}
                    />

                    <div className="font-medium">{t(paymentMethod.name)}</div>
                </div>
            );
        },
    },

    // {
    //     accessorKey: "is_status",
    //     header: t("Installed"),
    //     cell: ({ row }) =>
    //         row.original.is_status === "disabled"
    //             ? t("Not Installed Yet")
    //             : t("Installed"),
    // },

    {
        accessorKey: "Status",
        header: t("Status"),
        cell: ({ row }) =>
            CustomBadge(
                row.original.status === 1 ? t("Active") : t("Inactive"),
                row.original.status === 1
                    ? "bg-green-500 text-white dark:bg-green-800"
                    : "bg-red-500 text-white dark:bg-red-800",
            ),
    },

    {
        id: "Actions",
        header: t("Actions"),
        cell: ({ row }) => {
            const paymentMethod = row.original;

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
                        <DropdownMenuItem onClick={() => onEdit(paymentMethod)}>
                            <Pencil className="mr-2 size-4" />
                            {t("Edit")}
                        </DropdownMenuItem>

                        <DropdownMenuItem
                            onClick={() => onConfigure(paymentMethod)}
                        >
                            <Settings className="mr-2 size-4" />
                            {t("Configure")}
                        </DropdownMenuItem>

                        <DropdownMenuItem
                            onClick={() => onAction(paymentMethod)}
                        >
                            {paymentMethod.status === 0 ? (
                                <>
                                    <UserCheck className="mr-2 size-4" />
                                    {t("Activate")}
                                </>
                            ) : (
                                <>
                                    <UserX className="mr-2 size-4" />
                                    {t("Deactivate")}
                                </>
                            )}
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            );
        },
    },
];
