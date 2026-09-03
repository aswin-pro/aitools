import { CustomBadge } from "@/components/table/badge";
import { Button } from "@/components/ui/button";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { ColumnDef } from "@tanstack/react-table";
import {
    CheckCircle,
    MoreVertical,
    Pencil,
    XCircle,
} from "lucide-react";

export interface Plan {
    id: number;
    plan_id: string;
    is_private: boolean;
    name: string;
    description: string;
    price: number;
    validity: number;
    content_templates: Record<string, number>;
    ai_credits: number;
    ai_image_credits: number;
    speech_to_text: boolean;
    text_to_speech: boolean;
    code_generator: boolean;
    personalized_chat: boolean;
    document_analyzer: boolean;
    site_analyzer: boolean;
    is_recommended: boolean;
    customer_support: boolean;
    status: boolean;
}

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
    onEdit: (plan: Plan) => void;
    onAction: (
        plan: Plan,
        action: "active" | "inactive",
    ) => void;
}): ColumnDef<Plan>[] => [
    {
        accessorKey: "S_No",
        header: t("#"),
        cell: ({ row }) =>
            pageIndex * pageSize + row.index + 1,
    },

    {
        accessorKey: "Name",
        header: t("Name"),
        cell: ({ row }) => row.original.name,
    },

    {
        accessorKey: "Price",
        header: t("Price"),
        cell: ({ row }) => row.original.price,
    },

    {
        accessorKey: "Validity",
        header: t("Validity"),
        cell: ({ row }) => {
            const validity = row.original.validity;

            if (validity === 9999) {
                return t("Forever");
            }

            if (validity === 31) {
                return t("Monthly");
            }

            if (validity === 366) {
                return t("Yearly");
            }

            return `${validity} ${t("Days")}`;
        },
    },

    {
        accessorKey: "AI_Credits",
        header: t("AI Credits"),
        cell: ({ row }) => row.original.ai_credits,
    },

    {
        accessorKey: "AI_Image_Credits",
        header: t("Image Credits"),
        cell: ({ row }) => row.original.ai_image_credits,
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
            const plan = row.original;

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
                            onClick={() => onEdit(plan)}
                        >
                            <Pencil className="mr-2 size-4" />
                            {t("Edit")}
                        </DropdownMenuItem>

                        {plan.status ? (
                            <DropdownMenuItem
                                onClick={() =>
                                    onAction(plan, "inactive")
                                }
                            >
                                <XCircle className="mr-2 size-4" />
                                {t("Inactivate")}
                            </DropdownMenuItem>
                        ) : (
                            <DropdownMenuItem
                                onClick={() =>
                                    onAction(plan, "active")
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