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
import { Plus } from "lucide-react";
import { Button } from "@/components/ui/button";

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

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("System Translations")} />

            <Heading
                title={t("System Translations")}
                description={t(
                    "Manage translation languages and locales for your platform.",
                )}
            />

            <div className="mt-4">
                <div className="mb-4 flex justify-end">
                    <Button onClick={() => setCreateOpen(true)}>
                        <Plus className=" size-4" />
                        {t("Create New")}
                    </Button>
                </div>

                <DataTable
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
        </AppLayout>
    );
}
