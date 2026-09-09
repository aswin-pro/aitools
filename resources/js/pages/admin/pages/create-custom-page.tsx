import AppLayout from "@/layouts/app/app-layout";
import Heading from "@/components/heading";
import FormInput from "@/components/admin/form-input";
import FormTextarea from "@/components/admin/form-textarea";
import RichTextEditor from "@/components/admin/rich-text-editor";
import { Button } from "@/components/ui/button";
import { LoadingSwap } from "@/components/ui/loading-swap";
import { BreadcrumbItem } from "@/types";
import { Head, useForm } from "@inertiajs/react";
import { useTranslation } from "react-i18next";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";

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
        title: "Add Custom Page",
        href: "#",
    },
];

export default function CreateCustomPage() {
    const { t } = useTranslation();

    const { data, setData, post, processing, errors, clearErrors } = useForm({
        name: "Custom Page",
        title: "",
        slug: "",
        body: "",
        page_title: "",
        description: "",
        keywords: "",
    });

    const handleTitleChange = (value: string) => {
        setData("title", value);
        clearErrors("title");

        if (!data.slug) {
            const slug = value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, "")
                .replace(/\s+/g, "-")
                .replace(/-+/g, "-");

            setData("slug", slug);
            clearErrors("slug");
        }
    };

    const handleSubmit = () => {
        post(route("dashboard.admin.save.page"), {
            preserveScroll: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Add Custom Page")} />

            <div className="flex flex-col gap-4 p-4 md:p-6">
                <Heading
                t={t}
                    title={t("Add Custom Page")}
                    description={t("Create a new custom page")}
                />

                <Card>
                    <CardHeader>
                        <CardTitle>{t("Page Details")}</CardTitle>
                    </CardHeader>

                    <CardContent className="space-y-6">
                        <FormInput
                            id="title"
                            name="title"
                            type="text"
                            label={t("Title")}
                            required
                            value={data.title}
                            placeholder={t("Enter page title")}
                            error={errors.title}
                            disabled={processing}
                            onChange={(e) => handleTitleChange(e.target.value)}
                        />

                        <FormInput
                            id="slug"
                            name="slug"
                            type="text"
                            label={t("Slug")}
                            required
                            value={data.slug}
                            placeholder={t("Enter page slug")}
                            error={errors.slug}
                            disabled={processing}
                            onChange={(e) => {
                                clearErrors("slug");
                                setData("slug", e.target.value);
                            }}
                        />

                        <RichTextEditor
                            id="body"
                            label={t("Body")}
                            required
                            value={data.body}
                            error={errors.body}
                            disabled={processing}
                            onChange={(value) => {
                                clearErrors("body");
                                setData("body", value);
                            }}
                        />

                        <Card>
                            <CardHeader>
                                <CardTitle>{t("SEO Configurations")}</CardTitle>
                            </CardHeader>

                            <CardContent className="space-y-4">
                                <FormInput
                                    id="page_title"
                                    name="page_title"
                                    type="text"
                                    label={t("Page Title")}
                                    value={data.page_title}
                                    placeholder={t("Enter SEO page title")}
                                    error={errors.page_title}
                                    disabled={processing}
                                    onChange={(e) => {
                                        clearErrors("page_title");
                                        setData("page_title", e.target.value);
                                    }}
                                    required
                                />

                                <FormTextarea
                                    id="description"
                                    name="description"
                                    label={t("Description")}
                                    value={data.description}
                                    placeholder={t("Enter SEO description")}
                                    error={errors.description}
                                    disabled={processing}
                                    onChange={(e) => {
                                        clearErrors("description");
                                        setData("description", e.target.value);
                                    }}
                                    required
                                />

                                <FormInput
                                    id="keywords"
                                    name="keywords"
                                    type="text"
                                    label={t("Keywords")}
                                    value={data.keywords}
                                    placeholder={t("Enter SEO keywords")}
                                    error={errors.keywords}
                                    disabled={processing}
                                    onChange={(e) => {
                                        clearErrors("keywords");
                                        setData("keywords", e.target.value);
                                    }}
                                    required
                                />
                            </CardContent>
                        </Card>

                        <div className="flex justify-end pt-4">
                            <Button
                                type="button"
                                disabled={processing}
                                onClick={handleSubmit}
                            >
                                <LoadingSwap isLoading={processing}>
                                    {t("Save")}
                                </LoadingSwap>
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
