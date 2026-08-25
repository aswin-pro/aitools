import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import type { BreadcrumbItem } from "@/types";
import { Head } from "@inertiajs/react";
import { useTranslation } from "react-i18next";

import BlogForm from "./blog-form";

interface BlogCategory {
    blog_category_id: string;
    blog_category_title: string;
}

interface CreateBlogProps {
    blogsCategories: BlogCategory[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Blogs",
        href: route("dashboard.admin.blogs.post"),
    },
    {
        title: "Create",
        href: "#",
    },
];

export default function Create({
    blogsCategories,
}: CreateBlogProps) {
    const { t } = useTranslation();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Create Blog")} />

            <div className="mb-6">
                <Heading
                    title={t("Create Blog")}
                    description={t(
                        "Create and publish a new blog post",
                    )}
                />
            </div>

            <BlogForm
                mode="create"
                categories={blogsCategories}
            />
        </AppLayout>
    );
}