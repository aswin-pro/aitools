import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import { BreadcrumbItem, LaravelPagination, NavigateParams } from "@/types";
import { Form, Head, router } from "@inertiajs/react";
import { useMemo, useState } from "react";
import { useTranslation } from "react-i18next";
import { DataTable } from "@/components/table/data-table";
import { Currencies, Currency } from "@/types/admin";
import { getColumns } from "./columns";
import { useForm } from "@inertiajs/react";
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetFooter,
    SheetHeader,
    SheetTitle,
} from "@/components/ui/sheet";
import { Label } from "@/components/ui/label";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Currencies",
        href: "#",
    },
];

export default function Index({
    currencies,
}: {
    currencies: LaravelPagination<Currencies>;
}) {
    const { t } = useTranslation();

    //for sheet......
    const [sheetOpen, setSheetOpen] = useState(false);
    const [selectedCurrency, setSelectedCurrency] = useState<Currency | null>(
        null,
    );

    const form = useForm({
        id: "",
        name: "",
        iso_code: "",
        symbol: "",
        symbol_first: "false",
    });

    const navigate = (params: NavigateParams) => {
        router.reload({
            only: ["currencies"],
            data: params,
        });
    };

    const columns = useMemo(
        () =>
            getColumns({
                pageIndex: currencies.current_page - 1,
                pageSize: currencies.per_page,
                t,
                onEdit: (currency) => {
                    setSelectedCurrency(currency);
                    setSheetOpen(true);
                },
            }),
        [currencies.current_page, currencies.per_page, t],
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Currencies")} />

            <Heading
                title={t("Currencies")}
                description={t(
                    "Manage supported currencies, symbols, and currency formatting settings",
                )}
            />

            <div className="mt-4">
                <DataTable
                    columns={columns}
                    data={currencies.data}
                    pageIndex={currencies.current_page - 1}
                    pageSize={currencies.per_page}
                    totalCount={currencies.total}
                    initialSearch={route().params.search ?? ""}
                    onPageChange={(page) =>
                        navigate({
                            page: page + 1,
                            per_page: currencies.per_page,
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

<Sheet open={sheetOpen} onOpenChange={setSheetOpen}>
    <SheetContent className="flex flex-col sm:max-w-lg">
        <SheetHeader>
            <SheetTitle>{t("Update Currency")}</SheetTitle>

            <SheetDescription>
                {t(
                    "Update the currency details and formatting preferences.",
                )}
            </SheetDescription>
        </SheetHeader>

        <form
            onSubmit={(e) => {
                e.preventDefault();

                form.post(route("dashboard.admin.update.currency"), {
                    preserveScroll: true,

                    onSuccess: () => {
                        setSheetOpen(false);
                        form.reset();
                    },
                });
            }}
            className="flex h-full flex-col"
        >
            <div className="flex-1 space-y-5 overflow-y-auto px-4 py-6">
                {/* Name */}
                <div className="grid gap-2">
                    <Label htmlFor="currency-name">
                        {t("Name")}
                    </Label>

                    <Input
                        id="currency-name"
                        value={form.data.name}
                        onChange={(e) =>
                            form.setData("name", e.target.value)
                        }
                        placeholder={t("Currency name")}
                        disabled={form.processing}
                    />

                    {form.errors.name && (
                        <p className="text-sm text-destructive">
                            {form.errors.name}
                        </p>
                    )}
                </div>

                {/* ISO Code */}
                <div className="grid gap-2">
                    <Label htmlFor="currency-iso-code">
                        {t("ISO Code")}
                    </Label>

                    <Input
                        id="currency-iso-code"
                        value={form.data.iso_code}
                        onChange={(e) =>
                            form.setData("iso_code", e.target.value)
                        }
                        placeholder={t("ISO Code")}
                        disabled={form.processing}
                    />

                    {form.errors.iso_code && (
                        <p className="text-sm text-destructive">
                            {form.errors.iso_code}
                        </p>
                    )}
                </div>

                {/* Symbol */}
                <div className="grid gap-2">
                    <Label htmlFor="currency-symbol">
                        {t("Symbol")}
                    </Label>

                    <Input
                        id="currency-symbol"
                        value={form.data.symbol}
                        onChange={(e) =>
                            form.setData("symbol", e.target.value)
                        }
                        placeholder={t("Currency symbol")}
                        disabled={form.processing}
                    />

                    {form.errors.symbol && (
                        <p className="text-sm text-destructive">
                            {form.errors.symbol}
                        </p>
                    )}
                </div>

                {/* Symbol First */}
                <div className="grid gap-2">
                    <Label htmlFor="currency-symbol-first">
                        {t("Symbol First")}
                    </Label>

                    <Select
                        value={form.data.symbol_first}
                        onValueChange={(value) =>
                            form.setData("symbol_first", value)
                        }
                        disabled={form.processing}
                    >
                        <SelectTrigger id="currency-symbol-first">
                            <SelectValue
                                placeholder={t("Select option")}
                            />
                        </SelectTrigger>

                        <SelectContent>
                            <SelectItem value="true">
                                {t("Yes")}
                            </SelectItem>

                            <SelectItem value="false">
                                {t("No")}
                            </SelectItem>
                        </SelectContent>
                    </Select>

                    {form.errors.symbol_first && (
                        <p className="text-sm text-destructive">
                            {form.errors.symbol_first}
                        </p>
                    )}
                </div>
            </div>

            <SheetFooter className="w-full">
                <div className="flex flex-col w-full gap-4">
                <Button
                    type="submit"
                    disabled={form.processing}
                >
                    {form.processing
                        ? t("Updating...")
                        : t("Update")}
                </Button>

                <Button
                    type="button"
                    variant="outline"
                    onClick={() => setSheetOpen(false)}
                    disabled={form.processing}
                >
                    {t("Cancel")}
                </Button>
                </div>
                
            </SheetFooter>
        </form>
    </SheetContent>
</Sheet>
            </div>
        </AppLayout>
    );
}
