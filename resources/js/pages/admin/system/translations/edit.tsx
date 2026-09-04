import { useCallback, useEffect, useMemo, useRef } from "react";
import { Head, router, useForm } from "@inertiajs/react";

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

import {
    TranslationEditorRow,
    TranslationLanguage,
} from "@/types/translation-manager";
import { getEditColumns } from "./edit-columns";
import AppLayout from "@/layouts/app/app-layout";
import { DataTable } from "@/components/table/data-table";
import { BreadcrumbItem } from "@/types";

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

export default function Edit({
    locale,
    languages,
    files,
    selectedGroup,
    selectedType,
    search,
    sourceLocale,
    paginatedSourceKeys,
}: Props) {

    const initialTranslations = useMemo(() => {
        return paginatedSourceKeys.data.reduce<Record<string, string>>(
            (result, row) => {
                result[row.id] = row.translation ?? "";
                return result;
            },
            {},
        );
    }, [paginatedSourceKeys.data]);

    const form = useForm<{
        group: string;
        type: string;
        page: number;
        per_page: number;
        search: string;
        translations: Record<string, string>;
    }>({
        group: selectedGroup,
        type: selectedType,
        page: paginatedSourceKeys.current_page,
        per_page: paginatedSourceKeys.per_page,
        search: search ?? "",
        translations: initialTranslations,
    });

useEffect(() => {
    const incomingTranslations =
        paginatedSourceKeys.data.reduce<Record<string, string>>(
            (result, row) => {
                result[row.id] = row.translation ?? "";
                return result;
            },
            {},
        );

    form.setData("translations", {
        ...form.data.translations,
        ...incomingTranslations,
    });
}, [paginatedSourceKeys.data]);
    // const translationsRef = useRef(form.data.translations);

    // translationsRef.current = form.data.translations;
    const navigate = useCallback(
        ({
            page = paginatedSourceKeys.current_page,
            per_page = paginatedSourceKeys.per_page,
            search: searchValue = search,
        }: {
            page?: number;
            per_page?: number;
            search?: string;
        }) => {
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

    const handlePageChange = (pageIndex: number) => {
        navigate({
            page: pageIndex + 1,
        });
    };

    const handlePageSizeChange = (pageSize: number) => {
        navigate({
            page: 1,
            per_page: pageSize,
        });
    };

    const handleSearch = (value: string) => {
        navigate({
            page: 1,
            search: value,
        });
    };
    const handleTranslationChange = useCallback(
        (key: string, value: string) => {
            form.setData("translations", {
                ...form.data.translations,
                [key]: value,
            });
        },
        [form],
    );

    // const columns = useMemo(
    //     () =>
    //         getEditColumns({
    //             t: (key: string) => key,
    //             sourceLocale,
    //             locale,
    //             translations: translationsRef.current,
    //             onTranslationChange: handleTranslationChange,
    //         }),
    //     [
    //         sourceLocale,
    //         locale,
    //         handleTranslationChange,
    //     ],
    // );

const columns = useMemo(
    () =>
        getEditColumns({
            t: (key: string) => key,
            sourceLocale,
            locale,
            translations: form.data.translations,
            onTranslationChange: handleTranslationChange,
        }),
    [
        sourceLocale,
        locale,
        form.data.translations,
        handleTranslationChange,
    ],
);

const handleSubmit = (event: React.FormEvent) => {
    event.preventDefault();

    form.post(route("translation-manager.update", locale), {
        preserveScroll: true,
    });
};


    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Translation Editor" />

            <div className="space-y-6 p-6">
                {/* Header */}
                <div>
                    <h1 className="text-2xl font-semibold">
                        Translation Editor
                    </h1>

                    <p className="text-sm text-muted-foreground">
                        Manage and customize translated values for each locale
                        and category.
                    </p>
                </div>

                {/* Filters */}
                <div className="rounded-lg border bg-card p-4">
                    <div className="grid gap-4 md:grid-cols-2">
                        {/* Target Language */}
                        <div className="space-y-2">
                            <Label>Target Language</Label>

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
                                    <SelectValue placeholder="Select language" />
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

                        {/* Search */}
                        <div className="space-y-2">
                            <Label>Filter / Search</Label>

                            <Input
                                value={search}
                                placeholder="Type to search..."
                                onChange={(event) =>
                                    handleSearch(event.target.value)
                                }
                            />
                        </div>
                    </div>
                </div>

                {/* Editor */}
                <div className="flex items-center justify-between ">
                    <div>
                        <h2 className="font-semibold">
                            Editing Language Pack:{" "}
                            <span className="text-primary">
                                {locale.toUpperCase()}
                            </span>
                        </h2>
                    </div>

                    {paginatedSourceKeys.total > 0 && (
                        <Button
                            type="button"
                            onClick={handleSubmit}
                            disabled={form.processing}
                        >
                            {form.processing ? "Saving..." : "Save Changes"}
                        </Button>
                    )}
                </div>

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
            </div>
        </AppLayout>
    );
}