import { useMemo, useState } from "react";
import { useForm } from "@inertiajs/react";
import { useTranslation } from "react-i18next";
import { ImagePlus } from "lucide-react";

import FormInput from "@/components/admin/form-input";
import FormTextarea from "@/components/admin/form-textarea";
import { SearchableSelect } from "@/components/admin/searchable-select";
import { Button } from "@/components/ui/button";
import { LoadingSwap } from "@/components/ui/loading-swap";
import { Label } from "@/components/ui/label";

import { Blog } from "@/types/admin";

import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";

import RichTextEditor from "@/components/admin/rich-text-editor";
import InputError from "@/components/input-error";
import TagInput from "@/components/admin/tag-input";
import { toast } from "sonner";

interface BlogCategory {
    blog_category_id: string;
    blog_category_title: string;
}

interface BlogFormProps {
    mode: "create" | "edit";
    categories: BlogCategory[];
    blog?: Blog;
}

export default function BlogForm({ mode, categories, blog }: BlogFormProps) {
    const { t } = useTranslation();

    const isEdit = mode === "edit";

    // const [coverPreview, setCoverPreview] = useState<string | null>(
    //     blog?.cover_image ?? null,
    // );

    const form = useForm({
        blog_cover: null as File | null,
        blog_name: blog?.heading ?? "",
        blog_slug: blog?.slug ?? "",
        short_description: blog?.short_description ?? "",
        long_description: blog?.long_description ?? "",
        category_id: blog?.category ?? "",
        tags: blog?.tags ?? "",
        seo_title: blog?.title ?? "",
        seo_description: blog?.description ?? "",
        seo_keywords: blog?.keywords ?? "",
    });

    const fields = useMemo(
        () => [
            {
                type: "input" as const,
                name: "blog_name" as const,
                label: t("Name"),
                placeholder: t("Eg: Blog title"),
                required: true,
            },
            {
                type: "input" as const,
                name: "blog_slug" as const,
                label: t("Slug"),
                placeholder: t("Eg: blog-url"),
                required: true,
            },
            {
                type: "textarea" as const,
                name: "short_description" as const,
                label: t("Short description"),
                placeholder: t("Eg: Blog description"),
                required: true,
                rows: 3,
            },
        ],
        [t],
    );

    const seoFields = useMemo(
        () => [
            {
                type: "textarea" as const,
                name: "seo_title" as const,
                label: t("Title"),
                placeholder: t("SEO title"),
                required: true,
                rows: 1,
            },
            {
                type: "textarea" as const,
                name: "seo_description" as const,
                label: t("Description"),
                placeholder: t("SEO description"),
                required: true,
                rows: 5,
            },
            {
                type: "input" as const,
                name: "seo_keywords" as const,
                label: t("Keywords"),
                placeholder: t("SEO keywords"),
                required: true,
            },
        ],
        [t],
    );

    // const handleCoverChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    //     const file = e.target.files?.[0];

    //     if (!file) {
    //         return;
    //     }

    //     form.setData("blog_cover", file);

    //     setCoverPreview(URL.createObjectURL(file));

    //     form.clearErrors("blog_cover");
    // };

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        const options = {
            forceFormData: true,
            preserveScroll: true,

            onSuccess: () => {
                toast.success(
                    isEdit
                        ? t("Blog updated successfully!")
                        : t("Blog published successfully!"),
                );
            },

            onError: () => {
                toast.error(
                    isEdit
                        ? t("Unable to update blog.")
                        : t("Unable to publish blog."),
                );
            },
        };

        if (isEdit && blog) {
            form.post(
                route("dashboard.admin.update.blog", blog.blog_id),
                options,
            );
        } else {
            form.post(route("dashboard.admin.publish.blog"), options);
        }
    };

    return (
        <form onSubmit={handleSubmit} className="space-y-6">
            {/* Blog Information + Cover */}
            <div className="grid gap-6 lg:grid-cols-3">
                {/* Blog Information */}
                <Card className="lg:col-span-2">
                    <CardHeader>
                        <h2 className="text-lg font-semibold">
                            {t("Blog Information")}
                        </h2>

                        <CardDescription>
                            {t(
                                "Enter the basic information for your blog post.",
                            )}
                        </CardDescription>
                    </CardHeader>

                    <CardContent className="space-y-5">
                        <div className="grid gap-5 md:grid-cols-2 items-start">
                            <FormInput
                                name="blog_name"
                                label={t("Name")}
                                placeholder={t("Eg: Blog title")}
                                required
                                value={form.data.blog_name}
                                error={form.errors.blog_name}
                                disabled={form.processing}
                                onChange={(e) => {
                                    form.setData("blog_name", e.target.value);

                                    form.clearErrors("blog_name");
                                }}
                            />

                            <FormInput
                                name="blog_slug"
                                label={t("Slug")}
                                placeholder={t("Eg: blog-url")}
                                required
                                value={form.data.blog_slug}
                                error={form.errors.blog_slug}
                                disabled={form.processing}
                                onChange={(e) => {
                                    form.setData("blog_slug", e.target.value);

                                    form.clearErrors("blog_slug");
                                }}
                            />
                        </div>

                        {/* Short Description */}
                        <FormTextarea
                            name="short_description"
                            label={t("Short description")}
                            placeholder={t("Eg: Blog description")}
                            required
                            rows={4}
                            value={form.data.short_description}
                            error={form.errors.short_description}
                            disabled={form.processing}
                            onChange={(e) => {
                                form.setData(
                                    "short_description",
                                    e.target.value,
                                );

                                form.clearErrors("short_description");
                            }}
                        />
                    </CardContent>
                </Card>

                {/* Blog Settings */}
                <Card>
                    <CardHeader>
                        <h2 className="text-lg font-semibold">
                            {t("Blog Settings")}
                        </h2>

                        <CardDescription>
                            {t("Configure the cover, category and tags.")}
                        </CardDescription>
                    </CardHeader>

                    <CardContent className="space-y-5">
                        {/* Cover */}
                        <div className="grid gap-2">
                            <Label required={!isEdit}>{t("Cover")}</Label>

                            <input
                                type="file"
                                accept=".jpeg,.jpg,.png,.webp"
                                disabled={form.processing}
                                onChange={(e) => {
                                    const file = e.target.files?.[0];

                                    if (file) {
                                        form.setData("blog_cover", file);

                                        form.clearErrors("blog_cover");
                                    }
                                }}
                                className="block w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                            />

                            <InputError message={form.errors.blog_cover} />
                        </div>

                        {/* Category */}
                        <SearchableSelect
                            name="category_id"
                            label={t("Category")}
                            value={form.data.category_id}
                            options={categories.map((category) => ({
                                value: category.blog_category_id,
                                label: category.blog_category_title,
                            }))}
                            placeholder={t("Choose a category")}
                            searchable
                            error={form.errors.category_id}
                            onChange={(value) => {
                                form.setData("category_id", value);

                                form.clearErrors("category_id");
                            }}
                        />

                        <TagInput
                            label={t("Tags")}
                            value={form.data.tags}
                            onChange={(value) => {
                                form.setData("tags", value);
                                form.clearErrors("tags");
                            }}
                            error={form.errors.tags}
                            required
                            disabled={form.processing}
                            placeholder={t("Enter a tag")}
                            subLable={t("Press Enter or comma to add")}
                        />
                    </CardContent>
                </Card>
            </div>

            {/* Description */}
            <Card>
                <CardHeader>
                    <h2 className="text-lg font-semibold">
                        {t("Blog Content")}
                    </h2>

                    <CardDescription>
                        {t("Write the full content of your blog post.")}
                    </CardDescription>
                </CardHeader>

                <CardContent>
                    <RichTextEditor
                        label="Content"
                        value={form.data.long_description}
                        onChange={(value) => {
                            form.setData("long_description", value);

                            form.clearErrors("long_description");
                        }}
                        error={form.errors.long_description}
                        required
                        disabled={form.processing}
                    />
                </CardContent>
            </Card>

            {/* SEO */}
            <Card>
                <CardHeader>
                    <h2 className="text-lg font-semibold">
                        {t("SEO Configuration")}
                    </h2>

                    <CardDescription>
                        {t("Configure the SEO information for this blog.")}
                    </CardDescription>
                </CardHeader>

                <CardContent className="space-y-5">
                    <div className="grid gap-5 md:grid-cols-2 items-start">
                        <FormInput
                            name="seo_title"
                            label={t("Title")}
                            placeholder={t("SEO title")}
                            required
                            value={form.data.seo_title}
                            error={form.errors.seo_title}
                            disabled={form.processing}
                            onChange={(e) => {
                                form.setData("seo_title", e.target.value);

                                form.clearErrors("seo_title");
                            }}
                        />

                        <FormInput
                            name="seo_keywords"
                            label={t("Keywords")}
                            placeholder={t("SEO keywords")}
                            required
                            value={form.data.seo_keywords}
                            error={form.errors.seo_keywords}
                            disabled={form.processing}
                            onChange={(e) => {
                                form.setData("seo_keywords", e.target.value);

                                form.clearErrors("seo_keywords");
                            }}
                        />
                    </div>

                    {/* SEO Description */}
                    <FormTextarea
                        name="seo_description"
                        label={t("Description")}
                        placeholder={t("SEO description")}
                        required
                        rows={4}
                        value={form.data.seo_description}
                        error={form.errors.seo_description}
                        disabled={form.processing}
                        onChange={(e) => {
                            form.setData("seo_description", e.target.value);

                            form.clearErrors("seo_description");
                        }}
                    />
                </CardContent>
            </Card>

            {/* Actions */}
            <div className="flex justify-end gap-3">
                <Button
                    type="button"
                    variant="outline"
                    disabled={form.processing}
                    onClick={() => window.history.back()}
                >
                    {t("Cancel")}
                </Button>

                <Button type="submit" disabled={form.processing}>
                    <LoadingSwap isLoading={form.processing}>
                        {isEdit ? t("Update") : t("Publish")}
                    </LoadingSwap>
                </Button>
            </div>
        </form>
    );
}
