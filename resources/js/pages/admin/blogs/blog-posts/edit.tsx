import AppLayout from "@/layouts/app/app-layout";
import { Head } from "@inertiajs/react";
import { useTranslation } from "react-i18next";

import { BlogCategory, Blog } from "@/types/admin";
import BlogForm from "./blog-form";
import Heading from "@/components/heading";
import { BreadcrumbItem } from "@/types";

interface EditProps {
    blog: Blog;
    categories: BlogCategory[];
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
        title: "Edit",
        href: "#",
    },
];

export default function Edit({ blog, categories }: EditProps) {
    const { t } = useTranslation();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Edit Blog")} />

            <div className="mb-6">
                <Heading
                    title={t("Edit Blog")}
                    description={t("Update and manage your existing blog post")}
                />
            </div>

            <BlogForm blog={blog} mode="edit" categories={categories} />
        </AppLayout>
    );
}
