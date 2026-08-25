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

import RichTextEditor from "@/components/admin/rich-text-editor";

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

    const [coverPreview, setCoverPreview] = useState<string | null>(
        blog?.cover_image ?? null,
    );

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

    /*
     * Normal fields
     *
     * These are intentionally configuration based so we
     * don't repeat FormInput/FormTextarea markup.
     */
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

    const handleCoverChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];

        if (!file) {
            return;
        }

        form.setData("blog_cover", file);

        setCoverPreview(URL.createObjectURL(file));

        form.clearErrors("blog_cover");
    };

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        const options = {
            forceFormData: true,
            preserveScroll: true,

            onSuccess: () => {
                // Create/Edit page can decide what happens
                // after successful submission.
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
        <form onSubmit={handleSubmit} className="space-y-8">
            {/* Cover */}
            <div className="space-y-3">
                <Label required={!isEdit}>{t("Cover")}</Label>

                <input
                    type="file"
                    accept=".jpeg,.jpg,.png,.webp"
                    onChange={handleCoverChange}
                    disabled={form.processing}
                    className="block w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                />

                {form.errors.blog_cover && (
                    <p className="text-sm text-destructive">
                        {form.errors.blog_cover}
                    </p>
                )}

                {coverPreview && (
                    <div className="relative max-w-md overflow-hidden rounded-lg border">
                        <img
                            src={
                                coverPreview.startsWith("http")
                                    ? coverPreview
                                    : `/storage/${coverPreview}`
                            }
                            alt={t("Cover preview")}
                            className="h-auto max-h-80 w-full object-cover"
                        />
                    </div>
                )}

                {!coverPreview && (
                    <div className="flex h-40 max-w-md items-center justify-center rounded-lg border border-dashed text-muted-foreground">
                        <div className="flex flex-col items-center gap-2">
                            <ImagePlus className="size-8" />
                            <span className="text-sm">
                                {t("No cover image selected")}
                            </span>
                        </div>
                    </div>
                )}
            </div>

            {/* Basic information */}
            <div className="space-y-5">
                {fields.map((field) => {
                    const value = form.data[field.name];

                    if (field.type === "input") {
                        return (
                            <FormInput
                                key={field.name}
                                name={field.name}
                                label={field.label}
                                placeholder={field.placeholder}
                                required={field.required}
                                value={value}
                                error={form.errors[field.name]}
                                disabled={form.processing}
                                onChange={(e) => {
                                    form.setData(field.name, e.target.value);

                                    form.clearErrors(field.name);
                                }}
                            />
                        );
                    }

                    return (
                        <FormTextarea
                            key={field.name}
                            name={field.name}
                            label={field.label}
                            placeholder={field.placeholder}
                            required={field.required}
                            rows={field.rows}
                            value={value}
                            error={form.errors[field.name]}
                            disabled={form.processing}
                            onChange={(e) => {
                                form.setData(field.name, e.target.value);

                                form.clearErrors(field.name);
                            }}
                        />
                    );
                })}
            </div>

<RichTextEditor
    label={t("Description")}
    value={form.data.long_description}
    onChange={(value) => {
        form.setData("long_description", value);
        form.clearErrors("long_description");
    }}
    error={form.errors.long_description}
    required
    disabled={form.processing}
/>

            {/* Category + Tags */}
            <div className="grid gap-5 md:grid-cols-2">
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

                <FormInput
                    name="tags"
                    label={t("Tags")}
                    placeholder={t("tag 1, tag 2")}
                    required
                    value={form.data.tags}
                    error={form.errors.tags}
                    disabled={form.processing}
                    onChange={(e) => {
                        form.setData("tags", e.target.value);

                        form.clearErrors("tags");
                    }}
                    subLable={t("Separate tags with commas")}
                />
            </div>

            {/* SEO */}
            <div className="space-y-5">
                <div>
                    <h2 className="text-lg font-semibold">
                        {t("SEO Configurations")}
                    </h2>

                    <p className="text-sm text-muted-foreground">
                        {t("Configure the SEO information for this blog.")}
                    </p>
                </div>

                {seoFields.map((field) => {
                    const value = form.data[field.name];

                    if (field.type === "input") {
                        return (
                            <FormInput
                                key={field.name}
                                name={field.name}
                                label={field.label}
                                placeholder={field.placeholder}
                                required={field.required}
                                value={value}
                                error={form.errors[field.name]}
                                disabled={form.processing}
                                onChange={(e) => {
                                    form.setData(field.name, e.target.value);

                                    form.clearErrors(field.name);
                                }}
                            />
                        );
                    }

                    return (
                        <FormTextarea
                            key={field.name}
                            name={field.name}
                            label={field.label}
                            placeholder={field.placeholder}
                            required={field.required}
                            rows={field.rows}
                            value={value}
                            error={form.errors[field.name]}
                            disabled={form.processing}
                            onChange={(e) => {
                                form.setData(field.name, e.target.value);

                                form.clearErrors(field.name);
                            }}
                        />
                    );
                })}
            </div>

            {/* Actions */}
            <div className="flex justify-end gap-3 border-t pt-6">
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
