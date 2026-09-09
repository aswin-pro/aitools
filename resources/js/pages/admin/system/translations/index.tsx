import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import { BreadcrumbItem, LaravelPagination } from "@/types";
import { Head, router, useForm } from "@inertiajs/react";
import { useMemo, useState } from "react";
import { useTranslation } from "react-i18next";
import { DataTable } from "@/components/table/data-table";
import { getColumns } from "./columns";
import { TranslationLanguage } from "@/types/translation-manager";
import { FormSheet } from "@/components/admin/form-sheet";
import { Download, Plus, Trash2 } from "lucide-react";
import { Button } from "@/components/ui/button";
import { toast } from "sonner";
import { ConfirmDialog } from "@/components/admin/confirm-dialog";

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
        title: "System Translations",
        href: "#",
    },
];

interface Props {
    languages: LaravelPagination<TranslationLanguage>;
    allLanguages: Record<string, TranslationLanguage>;
    settings: Record<string, unknown> | null;
}

export default function Index({ languages, allLanguages, settings }: Props) {
    const { t } = useTranslation();
const [importOpen, setImportOpen] = useState(false);

const importForm = useForm<{
    locale: string;
    zip_file: File | null;
}>({
    locale: "",
    zip_file: null,
});

const [deleteOpen, setDeleteOpen] = useState(false);
const [languageToDelete, setLanguageToDelete] =
    useState<TranslationLanguage | null>(null);


const handleImportSubmit = (
    e: React.FormEvent<HTMLFormElement>,
) => {
    e.preventDefault();

    importForm.post(route("translation-manager.import"), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            setImportOpen(false);
            importForm.reset();

            toast.success(
                t(
                    "Translations successfully updated via imported ZIP package.",
                ),
            );
        },
        onError: () => {
            toast.error(
                t("Unable to import translation package."),
            );
        },
    });
};

const handleDelete = (language: TranslationLanguage) => {
    setLanguageToDelete(language);
    setDeleteOpen(true);
};

const handleDeleteConfirm = () => {
    if (!languageToDelete) return;

    router.delete(
        route(
            "translation-manager.destroy",
            languageToDelete.code,
        ),
        {
            preserveScroll: true,
            onSuccess: () => {
                setDeleteOpen(false);
                setLanguageToDelete(null);

                toast.success(
                    t("Language deleted successfully."),
                );
            },
            onError: () => {
                toast.error(
                    t("Unable to delete language."),
                );
            },
        },
    );
};

    const [createOpen, setCreateOpen] = useState(false);

    const createForm = useForm({
        name: "",
        code: "",
        copy_from: "en",
    });

const columns = useMemo(
    () =>
        getColumns({
            t,
            defaultLocale:
                typeof settings?.default_locale === "string"
                    ? settings.default_locale
                    : undefined,
            onDelete: handleDelete,
        }),
    [t, settings],
);

    const navigate = (params: {
        page?: number;
        per_page?: number;
        search?: string;
    }) => {
        router.get(route("translation-manager.index"), params, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const handleCreateSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        createForm.post(route("translation-manager.store"), {
            preserveScroll: true,
            onSuccess: () => {
                setCreateOpen(false);
                createForm.reset();
            },
        });
    };

    const createFields = [
        {
            type: "input" as const,
            name: "name",
            label: t("Language Name"),
            placeholder: "e.g., Tamil",
            required: true,
        },
        {
            type: "input" as const,
            name: "code",
            label: t("Language Code"),
            placeholder: "e.g., ta",
            required: true,
        },
        {
            type: "select" as const,
            name: "copy_from",
            label: t("Copy Base Content From"),
            placeholder: t("Select language"),
            searchable: true,
            options: Object.values(allLanguages).map((language) => ({
                value: language.code,
                label: `${t(language.name)} (${language.code})`,
            })),
        },
    ];
const importFields = [
    {
        type: "input" as const,
        name: "locale",
        label: t("Language Code"),
        placeholder: "e.g., ta",
        required: true,
    },
    {
        type: "file" as const,
        name: "zip_file",
        label: t("ZIP File"),
        accept: ".zip,application/zip,application/x-zip-compressed",
        required: true,
    },
];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("System Translations")} />

            <div className="flex justify-between  items-center">
                <div>
                    <Heading
                        t={t}
                        title={t("System Translations")}
                        description={t(
                            "Manage translation languages and locales for your platform.",
                        )}
                    />
                </div>

                <div className="flex gap-3 flex-wrap">
                        <Button
        variant="outline"
        onClick={() => setImportOpen(true)}
    >
        <Download className="size-4" />
        {t("Import")}
    </Button>
                    <Button onClick={() => setCreateOpen(true)}>
                        <Plus className=" size-4" />
                        {t("Create New")}
                    </Button>
                </div>
            </div>

            <div className="mt-4">
                <DataTable
                    t={t}
                    columns={columns}
                    data={languages.data}
                    pageIndex={languages.current_page - 1}
                    pageSize={languages.per_page}
                    totalCount={languages.total}
                    initialSearch={route().params.search ?? ""}
                    onPageChange={(page) =>
                        navigate({
                            page: page + 1,
                            per_page: languages.per_page,
                            search: route().params.search ?? "",
                        })
                    }
                    onPageSizeChange={(size) =>
                        navigate({
                            page: 1,
                            per_page: size,
                            search: route().params.search ?? "",
                        })
                    }
                    onSearch={(search) =>
                        navigate({
                            page: 1,
                            per_page: languages.per_page,
                            search,
                        })
                    }
                />
            </div>

            <FormSheet
                open={createOpen}
                onOpenChange={setCreateOpen}
                title={t("Create Language")}
                description={t(
                    "Initialize a new language folder structured for translation files and copy basic defaults.",
                )}
                form={createForm}
                fields={createFields}
                onSubmit={handleCreateSubmit}
                submitLabel={t("Save Language")}
                cancelLabel={t("Cancel")}
            />

<FormSheet
    open={importOpen}
    onOpenChange={setImportOpen}
    title={t("Import Translation")}
    description={t(
        "Upload a translation ZIP package for a language.",
    )}
    form={importForm}
    fields={importFields}
    onSubmit={handleImportSubmit}
    submitLabel={t("Import")}
    cancelLabel={t("Cancel")}
/>

<ConfirmDialog
    open={deleteOpen}
    onOpenChange={setDeleteOpen}
    icon={<Trash2 className="size-6" />}
    title={t("Delete Language")}
    description={
        languageToDelete
            ? t(
                  `Are you sure you want to delete the ${languageToDelete.name} language? This action cannot be undone.`,
              )
            : ""
    }
    confirmLabel={t("Delete")}
    cancelLabel={t("Cancel")}
    onConfirm={handleDeleteConfirm}
    loading={false}
/>
        </AppLayout>
    );
}
