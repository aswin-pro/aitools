

import AppLayout from "@/layouts/app/app-layout";
import Heading from "@/components/heading";
import { Button } from "@/components/ui/button";
import { Head, router, usePage } from "@inertiajs/react";
import { useEffect, useRef, useState } from "react";
import { useTranslation } from "react-i18next";
import grapesjs from "grapesjs";
import "grapesjs/dist/css/grapes.min.css";
import FormInput from "@/components/admin/form-input";
import FormTextarea from "@/components/admin/form-textarea";
import { LoadingSwap } from "@/components/ui/loading-swap";
import { BreadcrumbItem } from "@/types";

type PageData = {
    id: number;
    slug: string;
    name: string;
    title: string;
    body: string;
    page_title: string;
    description: string;
    keywords: string;
};

type PageProps = {
    page: PageData;
    theme: string;
};

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: "Dashboard",
            href: route("dashboard.admin.overview"),
        },
        {
            title: "Pages",
            href: "dashboard.admin.pages",
        },
        {
            title: "Page Builder",
            href: "#",
        },
    ];

export default function Editor() {
    const { t } = useTranslation();
    const { page } = usePage<PageProps>().props;

    const editorRef = useRef<HTMLDivElement>(null);
    const editorInstance = useRef<any>(null);

    const [pageTitle, setPageTitle] = useState(page.page_title ?? "");
    const [description, setDescription] = useState(page.description ?? "");
    const [keywords, setKeywords] = useState(page.keywords ?? "");
    const [saving, setSaving] = useState(false);

    useEffect(() => {
        if (!editorRef.current) {
            return;
        }

        const editor = grapesjs.init({
            container: editorRef.current,
            height: "600px",

            storageManager: false,

            components: page.body || "",

            canvas: {
                styles: [
                    "/themes/modern-orange/css/tailwind/tailwind.min.css",
                    "/css/all.css",
                    "/themes/modern-orange/css/main.css",
                ],
            },

            blockManager: {
                blocks: [],
            },
        });

        editorInstance.current = editor;

        return () => {
            editor.destroy();
            editorInstance.current = null;
        };
    }, [page.body]);

    const handleUpdate = () => {
        if (!editorInstance.current) {
            return;
        }

        setSaving(true);

        const body = editorInstance.current.getHtml();

        router.post(
            route("dashboard.admin.update.page", page.slug),
            {
                body,
                page_title: pageTitle,
                description,
                keywords,
            },
            {
                preserveScroll: true,

                onFinish: () => {
                    setSaving(false);
                },
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit ${page.title || page.name}`} />

            <div className="space-y-6">
                <Heading
                    title={`Edit ${page.title || page.name}`}
                    description={t("Edit your page visually")}
                />

                {/* GrapesJS Editor */}
                <div className="overflow-hidden rounded-lg border bg-white">
                    <div ref={editorRef} />
                </div>

                <div className="rounded-lg border bg-white p-6">
                    <div className="mb-6">
                        <h2 className="text-lg font-semibold">
                            {t("SEO Configuration")}
                        </h2>

                        <p className="text-sm text-muted-foreground">
                            {t("Configure the SEO information for this page.")}
                        </p>
                    </div>

                    <div className="space-y-5">
                        <FormInput
                            id="page_title"
                            name="page_title"
                            type="text"
                            label={t("Page Title")}
                            value={pageTitle}
                            onChange={(e) => setPageTitle(e.target.value)}
                            placeholder={t("Enter page title")}
                        />

                        <FormTextarea
                            id="description"
                            name="description"
                            label={t("Description")}
                            value={description}
                            onChange={(e) => setDescription(e.target.value)}
                            placeholder={t("Enter page description")}
                            rows={4}
                        />

                        <FormInput
                            id="keywords"
                            name="keywords"
                            type="text"
                            label={t("Keywords")}
                            value={keywords}
                            onChange={(e) => setKeywords(e.target.value)}
                            placeholder={t(
                                "Enter keywords separated by commas",
                            )}
                        />
                    </div>
                </div>

                {/* Update */}
                <div className="flex justify-end">
                    <Button
                        type="button"
                        onClick={handleUpdate}
                        disabled={saving}
                    >
                        <LoadingSwap isLoading={false}>
                            {t("Update")}
                        </LoadingSwap>
                    </Button>
                </div>
            </div>
        </AppLayout>
    );
}


