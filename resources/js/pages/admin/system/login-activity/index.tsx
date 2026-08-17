import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import {
    BreadcrumbItem,
    LaravelPagination,
    NavigateParams,
} from "@/types";
import { Head, router } from "@inertiajs/react";
import { useMemo } from "react";
import { useTranslation } from "react-i18next";
import { DataTable } from "@/components/table/data-table";
import { getColumns } from "./columns";
import { AuthenticationLog } from "@/types/admin";

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
        title: "Login Activity",
        href: "#",
    },
];

export default function Index({
    logs,
}: {
    logs: LaravelPagination<AuthenticationLog>;
}) {
    const { t } = useTranslation();

    const navigate = (params: NavigateParams) => {
        router.reload({
            only: ["logs"],
            data: params,
        });
    };

    const columns = useMemo(
        () =>
            getColumns({
                pageIndex: logs.current_page - 1,
                pageSize: logs.per_page,
                t,
            }),
        [logs.current_page, logs.per_page, t],
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Login Activity")} />

            <Heading
                title={t("Login Activity")}
                description={t(
                    "View and monitor user login and authentication activity",
                )}
            />

            <div className="mt-4">
                <DataTable
                    columns={columns}
                    data={logs.data}
                    pageIndex={logs.current_page - 1}
                    pageSize={logs.per_page}
                    totalCount={logs.total}
                    initialSearch={route().params.search ?? ""}
                    onPageChange={(page) =>
                        navigate({
                            page: page + 1,
                            per_page: logs.per_page,
                            search: route().params.search,
                        })
                    }
                    onPageSizeChange={(size) =>
                        navigate({
                            page: 1,
                            per_page: size,
                            search: route().params.search,
                        })
                    }
                    onSearch={(search) =>
                        navigate({
                            page: 1,
                            search,
                        })
                    }
                />
            </div>
        </AppLayout>
    );
}