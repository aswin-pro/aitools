import React, { useMemo } from "react";
import { Head, router } from "@inertiajs/react";
import { useTranslation } from "react-i18next";

import { Button } from "@/components/ui/button";

import { getColumns } from "./columns";
import AppLayout from "@/layouts/app/app-layout";
import { DataTable } from "@/components/table/data-table";
import { BreadcrumbItem } from "@/types";
import Heading from "@/components/heading";

interface Plan {
    id: number;
    plan_id: string;
    is_private: boolean;
    name: string;
    description: string;
    price: number;
    validity: number;
    content_templates: Record<string, number>;
    ai_credits: number;
    ai_image_credits: number;
    speech_to_text: boolean;
    text_to_speech: boolean;
    code_generator: boolean;
    personalized_chat: boolean;
    document_analyzer: boolean;
    site_analyzer: boolean;
    is_recommended: boolean;
    customer_support: boolean;
    status: boolean;
    created_at?: string;
    updated_at?: string;
}

interface PaginatedPlans {
    data: Plan[];
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
}

interface Props {
    plans: PaginatedPlans;
    filters: {
        search?: string;
        per_page?: number;
    };
}

export default function Index({ plans, filters }: Props) {
    const { t } = useTranslation();

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: t("Dashboard"),
            href: route("dashboard.admin.overview"),
        },
        {
            title: t("Plans"),
            href: "#",
        },
    ];

    const columns = useMemo(
        () =>
            getColumns({
                pageIndex: plans.current_page - 1,
                pageSize: plans.per_page,
                t,

                onEdit: (plan) => {
                    router.get(route("dashboard.admin.edit.plan", plan.id));
                },

                onAction: (plan, action) => {
                    router.get(
                        route("admin.delete.plan"),
                        {
                            id: plan.id,
                            action,
                        },
                        {
                            preserveScroll: true,
                        },
                    );
                },
            }),
        [plans.current_page, plans.per_page, t],
    );

    const navigate = (params: Record<string, string | number | undefined>) => {
        router.get(route("dashboard.admin.index.plans"), params, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Plans")} />

            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <Heading
                            title={t("Plans")}
                            description={t("Manage your subscription plans.")}
                        />
                    </div>

                    <Button
                        onClick={() =>
                            router.get(route("dashboard.admin.add.plan"))
                        }
                    >
                        {t("Add Plan")}
                    </Button>
                </div>

                <DataTable
                    columns={columns}
                    data={plans.data}
                    pageIndex={plans.current_page - 1}
                    pageSize={plans.per_page}
                    totalCount={plans.total}
                    initialSearch={filters.search ?? ""}
                    onPageChange={(page) =>
                        navigate({
                            page: page + 1,
                            per_page: plans.per_page,
                            search: filters.search,
                        })
                    }
                    onPageSizeChange={(size) =>
                        navigate({
                            page: 1,
                            per_page: size,
                            search: filters.search,
                        })
                    }
                    onSearch={(search) =>
                        navigate({
                            page: 1,
                            per_page: plans.per_page,
                            search,
                        })
                    }
                />
            </div>
        </AppLayout>
    );
}
