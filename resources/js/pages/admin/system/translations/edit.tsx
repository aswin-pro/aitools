import { useCallback, useMemo, useState } from "react";
import { Head, router, useForm } from "@inertiajs/react";
import { useTranslation } from "react-i18next";
import { toast } from "sonner";

import AppLayout from "@/layouts/app/app-layout";
import { DataTable } from "@/components/table/data-table";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";

import { BreadcrumbItem } from "@/types";
import {
    TranslationEditorRow,
    TranslationLanguage,
} from "@/types/translation-manager";

import { getEditColumns } from "./edit-columns";
import { LoadingSwap } from "@/components/ui/loading-swap";
import { Plus } from "lucide-react";
import { FormSheet } from "@/components/admin/form-sheet";

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface LaravelPagination<T> {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number | null;
    last_page: number;
    last_page_url: string;
    links: PaginationLink[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number | null;
    total: number;
}

interface Props {
    locale: string;
    languages: TranslationLanguage[];
    files: {
        name: string;
        type: string;
        relative: string;
    }[];
    selectedGroup: string;
    selectedType: string;
    search: string;
    sourceLocale: string;
    paginatedSourceKeys: LaravelPagination<TranslationEditorRow>;
    settings: unknown;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "System",
        href: route("dashboard.admin.system.login-activity"),
    },
    {
        title: "Translations",
        href: route("translation-manager.index"),
    },
    {
        title: "Update",
        href: "#",
    },
];

type NavigateParams = {
    page?: number;
    per_page?: number;
    search?: string;
};

export default function Edit({
    locale,
    languages,
    selectedGroup,
    selectedType,
    search,
    sourceLocale,
    paginatedSourceKeys,
}: Props) {
    const { t } = useTranslation();

    const translations = useMemo(() => {
        return paginatedSourceKeys.data.reduce<Record<string, string>>(
            (result, row) => {
                result[row.id] = row.translation ?? "";
                return result;
            },
            {},
        );
    }, [paginatedSourceKeys.data]);

    const form = useForm({
        group: selectedGroup,
        type: selectedType,
        page: paginatedSourceKeys.current_page,
        per_page: paginatedSourceKeys.per_page,
        search: search ?? "",
        translations,
    });

    const navigate = useCallback(
        ({
            page = paginatedSourceKeys.current_page,
            per_page = paginatedSourceKeys.per_page,
            search: searchValue = search,
        }: NavigateParams) => {
            router.get(
                route("translation-manager.edit", locale),
                {
                    page,
                    per_page,
                    search: searchValue,
                    group: selectedGroup,
                    type: selectedType,
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                },
            );
        },
        [
            locale,
            selectedGroup,
            selectedType,
            search,
            paginatedSourceKeys.current_page,
            paginatedSourceKeys.per_page,
        ],
    );

    const handlePageChange = useCallback(
        (pageIndex: number) => {
            navigate({
                page: pageIndex + 1,
            });
        },
        [navigate],
    );

    const handlePageSizeChange = useCallback(
        (pageSize: number) => {
            navigate({
                page: 1,
                per_page: pageSize,
            });
        },
        [navigate],
    );

    const handleSearch = useCallback(
        (value: string) => {
            navigate({
                page: 1,
                search: value,
            });
        },
        [navigate],
    );

    const [addKeyOpen, setAddKeyOpen] = useState(false);

const addKeyForm = useForm({
    source_value: "",
    target_value: "",
    locale: locale,
});

    const handleTranslationChange = useCallback(
        (key: string, value: string) => {
            form.setData("translations", {
                ...form.data.translations,
                [key]: value,
            });
        },
        [form],
    );

    const columns = useMemo(
        () =>
            getEditColumns({
                t,
                sourceLocale,
                locale,
                translations: form.data.translations,
                onTranslationChange: handleTranslationChange,
            }),
        [
            t,
            sourceLocale,
            locale,
            form.data.translations,
            handleTranslationChange,
        ],
    );

    const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        form.post(route("translation-manager.update", locale), {
            preserveScroll: true,

            onSuccess: () => {
                toast.success(t("Translations saved successfully."));
            },

            onError: () => {
                toast.error(t("Unable to save translations."));
            },
        });
    };

const handleAddKeySubmit = (
    e: React.FormEvent<HTMLFormElement>,
) => {
    e.preventDefault();

    addKeyForm.post(route("translation-manager.add-key"), {
        preserveScroll: true,

        onSuccess: () => {
            toast.success(
                t("New translation key added successfully."),
            );

            setAddKeyOpen(false);
            addKeyForm.reset();
        },

        onError: () => {
            toast.error(
                t("Unable to add translation key."),
            );
        },
    });
};

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Translation Editor")} />

            <div className="space-y-6 p-6">
                <div className="flex justify-between  items-center">
                    <div>
                        <h1 className="text-2xl font-semibold">
                            {t("Translation Editor")}
                        </h1>

                        <p className="text-sm text-muted-foreground">
                            {t(
                                "Manage and customize translated values for each locale and category.",
                            )}
                        </p>
                    </div>

                    <div>
                        <Button onClick={() => setAddKeyOpen(true)}>
                            <Plus className="size-4" />
                            {t("Add")}
                        </Button>
                    </div>
                </div>

                <div className="">
                    <div className="grid gap-4 md:grid-cols-2">
                        <div className="space-y-2">
                            <Label>{t("Target Language")}</Label>

                            <Select
                                value={locale}
                                onValueChange={(newLocale) => {
                                    router.get(
                                        route(
                                            "translation-manager.edit",
                                            newLocale,
                                        ),
                                        {
                                            group: selectedGroup,
                                            type: selectedType,
                                            search,
                                            page: 1,
                                            per_page:
                                                paginatedSourceKeys.per_page,
                                        },
                                        {
                                            preserveState: false,
                                            preserveScroll: true,
                                        },
                                    );
                                }}
                            >
                                <SelectTrigger>
                                    <SelectValue
                                        placeholder={t("Select language")}
                                    />
                                </SelectTrigger>

                                <SelectContent>
                                    {languages.map((language) => (
                                        <SelectItem
                                            key={language.code}
                                            value={language.code}
                                        >
                                            {language.name} ({language.code})
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>

                        {/* <div className="space-y-2">
                            <Label>{t("Filter / Search")}</Label>

                            <Input
                                value={search}
                                placeholder={t("Type to search...")}
                                onChange={(event) =>
                                    handleSearch(event.target.value)
                                }
                            />
                        </div> */}
                    </div>
                </div>

                <div className="flex items-center justify-between">
                    <div>
                        <h2 className="font-semibold">
                            {t("Editing Language Pack")}:{" "}
                            <span className="text-primary">
                                {locale.toUpperCase()}
                            </span>
                        </h2>
                    </div>

                    {paginatedSourceKeys.total > 0 && (
                        <Button
                            type="button"
                            onClick={() => {
                                const formElement = document.getElementById(
                                    "translation-form",
                                ) as HTMLFormElement | null;

                                formElement?.requestSubmit();
                            }}
                            disabled={form.processing}
                        >
                            <LoadingSwap isLoading={form.processing}>
                                {t("Save Changes")}
                            </LoadingSwap>
                        </Button>
                    )}
                </div>

                <form id="translation-form" onSubmit={handleSubmit}>
                    <DataTable
                        columns={columns}
                        data={paginatedSourceKeys.data}
                        pageIndex={paginatedSourceKeys.current_page - 1}
                        pageSize={paginatedSourceKeys.per_page}
                        totalCount={paginatedSourceKeys.total}
                        initialSearch={search ?? ""}
                        onPageChange={handlePageChange}
                        onPageSizeChange={handlePageSizeChange}
                        onSearch={handleSearch}
                    />
                </form>

<FormSheet
    open={addKeyOpen}
    onOpenChange={setAddKeyOpen}
    title={t("Add New Translation Word")}
    description={t(
        "Add a new translation key and its localized value.",
    )}
    form={addKeyForm}
    fields={[
        {
            type: "input",
            name: "source_value",
            label: t(
                `Source Word (${sourceLocale.toUpperCase()})`,
            ),
            placeholder: t("Default English Word"),
            required: true,
        },
        {
            type: "input",
            name: "target_value",
            label: t(
                `Localized Word (${locale.toUpperCase()})`,
            ),
            placeholder: t(
                "Local language translation (optional)",
            ),
        },
    ]}
    onSubmit={handleAddKeySubmit}
    submitLabel={t("Save")}
    cancelLabel={t("Cancel")}
/>
            </div>
        </AppLayout>
    );
}
