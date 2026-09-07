import AppLayout from "@/layouts/app/app-layout";
import Heading from "@/components/heading";
import FormInput from "@/components/admin/form-input";
import FormTextarea from "@/components/admin/form-textarea";
import RichTextEditor from "@/components/admin/rich-text-editor";
import { Button } from "@/components/ui/button";
import { LoadingSwap } from "@/components/ui/loading-swap";
import { BreadcrumbItem } from "@/types";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Head, useForm, usePage } from "@inertiajs/react";
import { useTranslation } from "react-i18next";

type PageData = {
    id: number;
    name: string;
    title: string;
    slug: string;
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
        title: "Edit Custom Page",
        href: "#",
    },
];

export default function EditCustomPage() {
    const { t } = useTranslation();
    const { page } = usePage<PageProps>().props;

    const {
        data,
        setData,
        post,
        processing,
        errors,
        clearErrors,
    } = useForm({
        page_id: page.id,
        title: page.title ?? "",
        slug: page.slug ?? "",
        body: page.body ?? "",
        page_title: page.page_title ?? "",
        description: page.description ?? "",
        keywords: page.keywords ?? "",
    });

    const handleSubmit = () => {
        post(route("dashboard.admin.update.custom.page"), {
            preserveScroll: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`${t("Edit")} ${page.title || page.name}`} />

            <div className="flex flex-col gap-4 p-4 md:p-6">
                <Heading
                    title={`${t("Edit")} ${page.title || page.name}`}
                    description={t("Edit your custom page")}
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
                            onChange={(e) => {
                                clearErrors("title");
                                setData("title", e.target.value);
                            }}
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
                                <CardTitle>
                                    {t("SEO Configurations")}
                                </CardTitle>
                            </CardHeader>

                            <CardContent className="space-y-4">
                                <FormInput
                                    id="page_title"
                                    name="page_title"
                                    type="text"
                                    label={t("Page Title")}
                                    required
                                    value={data.page_title}
                                    placeholder={t("Enter SEO page title")}
                                    error={errors.page_title}
                                    disabled={processing}
                                    onChange={(e) => {
                                        clearErrors("page_title");
                                        setData(
                                            "page_title",
                                            e.target.value,
                                        );
                                    }}
                                />

                                <FormTextarea
                                    id="description"
                                    name="description"
                                    label={t("Description")}
                                    required
                                    value={data.description}
                                    placeholder={t("Enter SEO description")}
                                    rows={4}
                                    error={errors.description}
                                    disabled={processing}
                                    onChange={(e) => {
                                        clearErrors("description");
                                        setData(
                                            "description",
                                            e.target.value,
                                        );
                                    }}
                                />

                                <FormInput
                                    id="keywords"
                                    name="keywords"
                                    type="text"
                                    label={t("Keywords")}
                                    required
                                    value={data.keywords}
                                    placeholder={t("Enter SEO keywords")}
                                    error={errors.keywords}
                                    disabled={processing}
                                    onChange={(e) => {
                                        clearErrors("keywords");
                                        setData("keywords", e.target.value);
                                    }}
                                />
                            </CardContent>
                        </Card>

                        <div className="flex justify-end pt-4">
                            <Button
                                type="button"
                                onClick={handleSubmit}
                                disabled={processing}
                            >
                                <LoadingSwap isLoading={processing}>
                                    {t("Update")}
                                </LoadingSwap>
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}