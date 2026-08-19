import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import { BreadcrumbItem, LaravelPagination, NavigateParams, SharedData } from "@/types";
import { Form, Head, router, usePage } from "@inertiajs/react";
import { useEffect, useMemo, useRef, useState } from "react";
import { useTranslation } from "react-i18next";
import { DataTable } from "@/components/table/data-table";
import { Currencies, Currency } from "@/types/admin";
import { getColumns } from "./columns";
import { useForm } from "@inertiajs/react";
import { FormSheet } from "@/components/admin/form-sheet";
import { Button } from "@/components/ui/button";
import { CircleDollarSign } from "lucide-react";
import { toast } from "sonner";

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

    const [createSheetOpen, setCreateSheetOpen] = useState(false);

    const form = useForm({
        id: '',
        name: "",
        iso_code: "",
        iso_numeric: "",
        symbol: "",
        subunit: "",
        subunit_to_unit: "",
        symbol_first: "true",
        html_entity: "",
        decimal_mark: "",
        thousands_separator: "",
    });

    const createForm = useForm({
        name: "",
        iso_code: "",
        iso_numeric: "",
        symbol: "",
        subunit: "",
        subunit_to_unit: "",
        symbol_first: "true",
        html_entity: "",
        decimal_mark: "",
        thousands_separator: "",
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
                    form.setData({
                        id: String(currency.id),
                        name: currency.name,
                        iso_code: currency.iso_code,
                        symbol: currency.symbol,
                        symbol_first: currency.symbol_first,
                        iso_numeric: currency.iso_numeric,
                        subunit: currency.subunit,
                        subunit_to_unit: currency.subunit_to_unit,
                        html_entity: currency.html_entity,
                        decimal_mark: currency.decimal_mark,
                        thousands_separator: currency.thousands_separator
                    });

                    form.clearErrors();

                    setSheetOpen(true);
                },
            }),
        [currencies.current_page, currencies.per_page, t],
    );

    const handleCreate = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        createForm.post(route("dashboard.admin.create.currency"), {
            preserveScroll: true,

            onSuccess: () => {
                setCreateSheetOpen(false);
                createForm.reset();
            },
        });
    };

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        form.post(route("dashboard.admin.update.currency"), {
            preserveScroll: true,

            onSuccess: () => {
                setSheetOpen(false);
                form.reset();
            },
        });
    };

    const { flash } = usePage<SharedData>().props;

    const lastMessage = useRef<string | null>(null);

    useEffect(() => {
        if (flash?.success && lastMessage.current !== flash.success) {
            toast.success(flash.success);
            lastMessage.current = flash.success;
        }

        if (flash?.error && lastMessage.current !== flash.error) {
            toast.error(flash.error);
            lastMessage.current = flash.error;
        }
    }, [flash]);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Currencies")} />

            <div className="flex justify-between items-center">
                <Heading
                    title={t("Currencies")}
                    description={t(
                        "Manage supported currencies, symbols, and currency formatting settings",
                    )}
                />

                <Button onClick={() => setCreateSheetOpen(true)}>
                    <CircleDollarSign />
                    {t("Create Currency")}
                </Button>
            </div>

            <div className="">
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
                <FormSheet
                    open={sheetOpen}
                    onOpenChange={setSheetOpen}
                    title={t("Update Currency")}
                    description={t(
                        "Update the currency details and formatting preferences.",
                    )}
                    form={form}
                    onSubmit={handleSubmit}
                    fields={[
                        {
                            type: "input",
                            name: "name",
                            label: t("Name"),
                            placeholder: t("Currency name"),
                            required: true,
                        },
                        {
                            type: "input",
                            name: "iso_code",
                            label: t("ISO Code"),
                            placeholder: t("Example: INR"),
                            required: true,
                        },
                        {
                            type: "input",
                            name: "iso_numeric",
                            label: t("ISO Numeric"),
                            placeholder: t("Example: 356"),
                        },
                        {
                            type: "input",
                            name: "symbol",
                            label: t("Symbol"),
                            placeholder: t("Example: ₹"),
                            required: true,
                        },
                        {
                            type: "input",
                            name: "subunit",
                            label: t("Subunit"),
                            placeholder: t("Example: Paise"),
                        },
                        {
                            type: "input",
                            name: "subunit_to_unit",
                            label: t("Subunit to Unit"),
                            placeholder: t("Example: 100"),
                        },
                        {
                            type: "select",
                            name: "symbol_first",
                            label: t("Symbol First"),
                            placeholder: t("Select symbol position"),
                            searchable: false,
                            options: [
                                {
                                    value: "true",
                                    label: t("Yes"),
                                },
                                {
                                    value: "false",
                                    label: t("No"),
                                },
                            ],
                        },
                        {
                            type: "input",
                            name: "html_entity",
                            label: t("HTML Entity"),
                            placeholder: t("Example: &#8377;"),
                        },
                        {
                            type: "input",
                            name: "decimal_mark",
                            label: t("Decimal Mark"),
                            placeholder: t("Enter decimal places. E.g., ."),
                        },
                        {
                            type: "input",
                            name: "thousands_separator",
                            label: t("Thousands Separator"),
                            placeholder: t("Enter thousand separator. E.g., ,"),
                        },
                    ]}
                />

                <FormSheet
                    open={createSheetOpen}
                    onOpenChange={setCreateSheetOpen}
                    title={t("Create Currency")}
                    description={t(
                        "Add a new currency and configure its formatting preferences.",
                    )}
                    form={createForm}
                    onSubmit={handleCreate}
                    submitLabel={t("Create")}
                    cancelLabel={t("Cancel")}
                    fields={[
                        {
                            type: "input",
                            name: "name",
                            label: t("Name"),
                            placeholder: t("Currency name"),
                            required: true,
                        },
                        {
                            type: "input",
                            name: "iso_code",
                            label: t("ISO Code"),
                            placeholder: t("Example: INR"),
                            required: true,
                        },
                        {
                            type: "input",
                            name: "iso_numeric",
                            label: t("ISO Numeric"),
                            placeholder: t("Example: 356"),
                        },
                        {
                            type: "input",
                            name: "symbol",
                            label: t("Symbol"),
                            placeholder: t("Example: ₹"),
                            required: true,
                        },
                        {
                            type: "input",
                            name: "subunit",
                            label: t("Subunit"),
                            placeholder: t("Example: Paise"),
                        },
                        {
                            type: "input",
                            name: "subunit_to_unit",
                            label: t("Subunit to Unit"),
                            placeholder: t("Example: 100"),
                        },
                        {
                            type: "select",
                            name: "symbol_first",
                            label: t("Symbol First"),
                            placeholder: t("Select symbol position"),
                            searchable: false,
                            options: [
                                {
                                    value: "true",
                                    label: t("Yes"),
                                },
                                {
                                    value: "false",
                                    label: t("No"),
                                },
                            ],
                        },
                        {
                            type: "input",
                            name: "html_entity",
                            label: t("HTML Entity"),
                            placeholder: t("Example: &#8377;"),
                        },
                        {
                            type: "input",
                            name: "decimal_mark",
                            label: t("Decimal Mark"),
                            placeholder: t("Enter decimal places. E.g., ."),
                        },
                        {
                            type: "input",
                            name: "thousands_separator",
                            label: t("Thousands Separator"),
                            placeholder: t("Enter thousand separator. E.g., ,"),
                        },
                    ]}
                />
            </div>
        </AppLayout>
    );
}
