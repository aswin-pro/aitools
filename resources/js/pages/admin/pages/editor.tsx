import AppLayout from "@/layouts/app/app-layout";
import Heading from "@/components/heading";
import FormInput from "@/components/admin/form-input";
import FormTextarea from "@/components/admin/form-textarea";
import { Button } from "@/components/ui/button";
import { LoadingSwap } from "@/components/ui/loading-swap";
import { BreadcrumbItem } from "@/types";
import { Head, useForm, usePage } from "@inertiajs/react";
import { useEffect, useRef } from "react";
import { useTranslation } from "react-i18next";
import grapesjs from "grapesjs";
import "grapesjs/dist/css/grapes.min.css";

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
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Pages",
        href: route("dashboard.admin.pages"),
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
    const editor = useRef<any>(null);

    const { data, setData, post, processing, errors, clearErrors } = useForm({
        body: page.body ?? "",
        page_title: page.page_title ?? "",
        description: page.description ?? "",
        keywords: page.keywords ?? "",
    });

    useEffect(() => {
        if (!editorRef.current) {
            return;
        }

        editor.current = grapesjs.init({
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

            assetManager: {
                upload: route("dashboard.admin.pages.upload.image"),
                uploadName: "file",
                autoAdd: true,
                multiUpload: false,

                headers: {
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") ?? "",
                    Accept: "application/json",
                },
            },

            deviceManager: {
                devices: [
                    {
                        id: "desktop",
                        name: "Desktop",
                        width: "",
                    },
                    {
                        id: "tablet",
                        name: "Tablet",
                        width: "768px",
                        widthMedia: "992px",
                    },
                    {
                        id: "mobile",
                        name: "Mobile",
                        width: "320px",
                        widthMedia: "480px",
                    },
                ],
            },

            styleManager: {
                sectors: [
                    {
                        id: "classes",
                        name: "Classes",
                        open: true,
                        properties: [],
                    },
                ],
            },

            blockManager: {
                blocks: [],
            },
        });

        return () => {
            editor.current?.destroy();
            editor.current = null;
        };
    }, []);

    const handleUpdate = () => {
        if (!editor.current) {
            return;
        }

        const html = editor.current.getHtml();
        const css = editor.current.getCss();

        const body = `
<style>
${css}
</style>
${html}
`.trim();

        setData("body", body);

        post(route("dashboard.admin.update.page", page.slug), {
            preserveScroll: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit ${page.title || page.name}`} />

            <div className="space-y-6">
                <Heading
                    title={`Edit ${page.title || page.name}`}
                    description={t("Edit your page visually")}
                />

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
                            required
                            value={data.page_title}
                            placeholder={t("Enter page title")}
                            error={errors.page_title}
                            disabled={processing}
                            onChange={(e) => {
                                clearErrors("page_title");
                                setData("page_title", e.target.value);
                            }}
                        />

                        <FormTextarea
                            id="description"
                            name="description"
                            label={t("Description")}
                            required
                            value={data.description}
                            placeholder={t("Enter page description")}
                            rows={4}
                            error={errors.description}
                            disabled={processing}
                            onChange={(e) => {
                                clearErrors("description");
                                setData("description", e.target.value);
                            }}
                        />

                        <FormInput
                            id="keywords"
                            name="keywords"
                            type="text"
                            label={t("Keywords")}
                            required
                            value={data.keywords}
                            placeholder={t(
                                "Enter keywords separated by commas",
                            )}
                            error={errors.keywords}
                            disabled={processing}
                            onChange={(e) => {
                                clearErrors("keywords");
                                setData("keywords", e.target.value);
                            }}
                        />
                    </div>
                </div>

                <div className="flex justify-end">
                    <Button
                        type="button"
                        onClick={handleUpdate}
                        disabled={processing}
                    >
                        <LoadingSwap isLoading={processing}>
                            {t("Update")}
                        </LoadingSwap>
                    </Button>
                </div>
            </div>
        </AppLayout>
    );
}
