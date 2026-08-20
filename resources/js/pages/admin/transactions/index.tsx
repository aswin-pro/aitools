import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import {
    LaravelPagination,
    NavigateParams,
    Transaction,
    type BreadcrumbItem,
} from "@/types";
import { Head, router } from "@inertiajs/react";
import { useMemo } from "react";
import { useTranslation } from "react-i18next";
import { DataTable } from "@/components/table/data-table";
import { getColumns } from "./columns";
import { CheckIcon, SlidersHorizontal } from "lucide-react";
import { cn } from "@/lib/utils";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/ui/popover";
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandItem,
    CommandList,
} from "@/components/ui/command";
import { Button } from "@/components/ui/button";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Transactions",
        href: "#",  
    },
];

export default function Index({
    transactions,
    transactionType,
}: {
    transactions: LaravelPagination<Transaction>;
    transactionType: string;
}) {
    const { t } = useTranslation();

    const navigate = (params: NavigateParams) => {
        router.reload({
            only: ["transactions"],
            data: params,
        });
    };

    const columns = useMemo(
        () =>
            getColumns({
                pageIndex: transactions.current_page - 1,
                t,
            }),
        [transactions.current_page, t],
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Transactions" />

            <div className="mb-4">
                <Heading
                    title="Transactions"
                    description="View and manage customer transactions"
                />
            </div>

            <div className="mb-4 flex justify-end">

        <Popover>
            <PopoverTrigger asChild>
                <Button variant="outline" className="px-3">
                    <SlidersHorizontal />
                    {t("Transaction Type")}
                </Button>
            </PopoverTrigger>

            <PopoverContent align="end" className="w-44 p-0">
                <Command>
                    <CommandList>
                        <CommandEmpty>
                            {t("No transaction types found.")}
                        </CommandEmpty>

                        <CommandGroup>
                            <CommandItem
                                onSelect={() => {
                                    router.reload({
                                        only: [
                                            "transactions",
                                            "transactionType",
                                        ],
                                        data: {
                                            transaction_type: "all",
                                            page: 1,
                                            per_page: transactions.per_page,
                                            search: route().params.search,
                                        },
                                    });
                                }}
                            >
                                <span>{t("All")}</span>

                                <CheckIcon
                                    className={cn(
                                        "ml-auto size-4",
                                        transactionType === "all"
                                            ? "opacity-100"
                                            : "opacity-0",
                                    )}
                                />
                            </CommandItem>

                            <CommandItem
                                onSelect={() => {
                                    router.reload({
                                        only: [
                                            "transactions",
                                            "transactionType",
                                        ],
                                        data: {
                                            transaction_type: "online",
                                            page: 1,
                                            per_page: transactions.per_page,
                                            search: route().params.search,
                                        },
                                    });
                                }}
                            >
                                <span>{t("Online")}</span>

                                <CheckIcon
                                    className={cn(
                                        "ml-auto size-4",
                                        transactionType === "online"
                                            ? "opacity-100"
                                            : "opacity-0",
                                    )}
                                />
                            </CommandItem>

                            <CommandItem
                                onSelect={() => {
                                    router.reload({
                                        only: [
                                            "transactions",
                                            "transactionType",
                                        ],
                                        data: {
                                            transaction_type: "offline",
                                            page: 1,
                                            per_page: transactions.per_page,
                                            search: route().params.search,
                                        },
                                    });
                                }}
                            >
                                <span>{t("Offline")}</span>

                                <CheckIcon
                                    className={cn(
                                        "ml-auto size-4",
                                        transactionType === "offline"
                                            ? "opacity-100"
                                            : "opacity-0",
                                    )}
                                />
                            </CommandItem>
                        </CommandGroup>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>

</div>

            <DataTable
                columns={columns}
                data={transactions.data}
                pageIndex={transactions.current_page - 1}
                pageSize={transactions.per_page}
                totalCount={transactions.total}
                initialSearch={route().params.search ?? ""}
                onPageChange={(page) =>
                    navigate({
                        page: page + 1,
                        per_page: transactions.per_page,
                        search: route().params.search,
                    })
                }
                onPageSizeChange={(size) =>
                    navigate({
                        page: 1,
                        per_page: size,
                        search: route().params.search,
                    })
                }
                onSearch={(search) =>
                    navigate({
                        page: 1,
                        search,
                    })
                }
            />
        </AppLayout>
    );
}