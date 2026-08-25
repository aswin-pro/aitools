import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import { LaravelPagination, type BreadcrumbItem } from "@/types";
import { Blog } from "@/types/admin";
import { Head, router } from "@inertiajs/react";
import { useMemo } from "react";
import { useTranslation } from "react-i18next";
import { Plus } from "lucide-react";

import { DataTable } from "@/components/table/data-table";
import { Button } from "@/components/ui/button";

import { getColumns } from "./columns";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Blogs",
        href: "#",
    },
];

export default function Index({
    blogs,
}: {
    blogs: LaravelPagination<Blog>;
}) {
    const { t } = useTranslation();

    const columns = useMemo(
        () =>
            getColumns({
                t,
                onEdit: (blog) => {
                    router.get(
                        route(
                            "dashboard.admin.edit.blog",
                            blog.blog_id,
                        ),
                    );
                },
                onAction: () => {
                    
                },
            }),
        [t],
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Blogs")} />

            <div className="mb-4 flex items-start justify-between">
                <Heading
                    title={t("Blogs")}
                    description={t(
                        "Create and manage your blog posts",
                    )}
                />

                <Button
                    onClick={() =>
                        router.get(
                            route("dashboard.admin.create.blog"),
                        )
                    }
                >
                    <Plus />
                    {t("Create")}
                </Button>
            </div>

            <DataTable
                columns={columns}
                data={blogs.data}
                pageIndex={blogs.current_page - 1}
                pageSize={blogs.per_page}
                totalCount={blogs.total}
                initialSearch={route().params.search ?? ""}
                onPageChange={(page) =>
                    router.get(
                        route("dashboard.admin.blogs.post"),
                        {
                            page: page + 1,
                            per_page: blogs.per_page,
                            search: route().params.search,
                        },
                        {
                            preserveState: true,
                            preserveScroll: true,
                        },
                    )
                }
                onPageSizeChange={(size) =>
                    router.get(
                        route("dashboard.admin.blogs.post"),
                        {
                            page: 1,
                            per_page: size,
                            search: route().params.search,
                        },
                        {
                            preserveState: true,
                            preserveScroll: true,
                        },
                    )
                }
                onSearch={(search) =>
                    router.get(
                        route("dashboard.admin.blogs.post"),
                        {
                            page: 1,
                            per_page: blogs.per_page,
                            search,
                        },
                        {
                            preserveState: true,
                            preserveScroll: true,
                        },
                    )
                }
            />
        </AppLayout>
    );
}