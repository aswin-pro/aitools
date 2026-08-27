import Heading from "@/components/heading";
import AppLayout from "@/layouts/app/app-layout";
import {
    BreadcrumbItem,
    LaravelPagination,
    NavigateParams,
    SharedData,
} from "@/types";
import { Head, router, useForm, usePage } from "@inertiajs/react";
import { useEffect, useMemo, useRef, useState } from "react";
import { useTranslation } from "react-i18next";
import { DataTable } from "@/components/table/data-table";
import { Currencies, Plan, User } from "@/types/admin";
import { toast } from "sonner";
import { getColumns } from "./columns";
import { FormSheet } from "@/components/admin/form-sheet";
import { Trash2, UserCheck, UserX } from "lucide-react";
import { ConfirmDialog } from "@/components/admin/confirm-dialog";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Currencies",
        href: "#",
    },
];

export default function Index({
    users,
    plans,
}: {
    users: LaravelPagination<User>;
    plans: Plan[];
}) {
    const { t } = useTranslation();

    const [confirmOpen, setConfirmOpen] = useState(false);
    const [selectedAction, setSelectedAction] = useState<
        "activate" | "deactivate" | "delete" | null
    >(null);
    //for sheet......
    const [editOpen, setEditOpen] = useState(false);
    const [selectedUser, setSelectedUser] = useState<User | null>(null);

    const [planOpen, setPlanOpen] = useState(false);

    const [actionLoading, setActionLoading] = useState(false);

    const handleEdit = (user: User) => {
        setSelectedUser(user);

        form.setData({
            user_id: String(user.id),
            full_name: user.name,
            email: user.email,
            password: "",
        });

        form.clearErrors();
        setEditOpen(true);
    };

    const openActionDialog = (
        user: User,
        action: "activate" | "deactivate" | "delete",
    ) => {
        setSelectedUser(user);
        setSelectedAction(action);
        setConfirmOpen(true);
    };

    const handleAction = () => {
        if (!selectedUser || !selectedAction) {
            return;
        }

        setActionLoading(true);

        const routeName =
            selectedAction === "delete"
                ? "dashboard.admin.delete.user"
                : "dashboard.admin.update.status";

        router.get(
            route(routeName),
            {
                id: selectedUser.id,
                ...(selectedAction !== "delete" && {
                    mode: selectedAction,
                }),
            },
            {
                preserveScroll: true,

                onSuccess: () => {
                    setConfirmOpen(false);
                    setSelectedUser(null);
                    setSelectedAction(null);
                },

                onError: () => {
                    toast.error(t("Unable to complete the action."));
                },

                onFinish: () => {
                    setActionLoading(false);
                },
            },
        );
    };

    const handleUpdate = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        form.post(route("dashboard.admin.update.user"), {
            preserveScroll: true,

            onSuccess: () => {
                setEditOpen(false);
                setSelectedUser(null);

                form.reset("password");

                toast.success(t("User updated successfully!"));
            },

            onError: () => {
                toast.error(t("Please check the form errors."));
            },
        });
    };

    const handleChangePlan = (user: User) => {
        setSelectedUser(user);

        planForm.setData({
            user_id: String(user.id),
            plan_id: user.plan_id ? String(user.plan_id) : "",
        });

        planForm.clearErrors();

        setPlanOpen(true);
    };

    const handleChangePlanSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        planForm.post(route("dashboard.admin.update.user.plan"), {
            preserveScroll: true,

            onSuccess: () => {
                setPlanOpen(false);
                setSelectedUser(null);

                planForm.reset();

                toast.success(t("Plan changed successfully!"));
            },

            onError: () => {
                toast.error(t("Please check the form errors."));
            },
        });
    };

    const navigate = (params: NavigateParams) => {
        router.reload({
            only: ["users"],
            data: params,
        });
    };

    const dialogContent = {
        activate: {
            icon: <UserCheck className="size-7 text-green-600" />,
            title: t("Activate user?"),
            description: t("If you proceed, this user will be activated."),
            confirmLabel: t("Yes, activate"),
        },

        deactivate: {
            icon: <UserX className="size-7 text-destructive" />,
            title: t("Deactivate user?"),
            description: t("If you proceed, this user will be deactivated."),
            confirmLabel: t("Yes, deactivate"),
        },
        delete: {
            icon: <Trash2 className="size-7 text-destructive" />,
            title: t("Delete user?"),
            description: t(
                "This will permanently delete the user and their related data. This action cannot be undone.",
            ),
            confirmLabel: t("Yes, delete"),
        },
    };

    const currentDialog = selectedAction ? dialogContent[selectedAction] : null;

    const columns = useMemo(
        () =>
            getColumns({
                pageIndex: users.current_page - 1,
                pageSize: users.per_page,
                t,
                onEdit: handleEdit,
                onChangePlan: handleChangePlan,
                onAction: openActionDialog,
            }),
        [users.current_page, users.per_page, t],
    );

    const form = useForm({
        user_id: "",
        full_name: "",
        email: "",
        password: "",
    });

    const planForm = useForm({
        user_id: "",
        plan_id: "",
    });

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Currencies")} />

            <Heading
                title={t("Users")}
                description={t(
                    "Manage registered users, plans, and account details",
                )}
            />

            <div className="">
                <DataTable
                    columns={columns}
                    data={users.data}
                    pageIndex={users.current_page - 1}
                    pageSize={users.per_page}
                    totalCount={users.total}
                    initialSearch={route().params.search ?? ""}
                    onPageChange={(page) =>
                        navigate({
                            page: page + 1,
                            per_page: users.per_page,
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

            <FormSheet
                open={editOpen}
                onOpenChange={setEditOpen}
                title={t("Edit User")}
                description={t("Update user account details")}
                form={form}
                fields={[
                    {
                        type: "input",
                        name: "full_name",
                        label: t("Full Name"),
                        placeholder: t("Enter full name"),
                        required: true,
                        inputType: "text",
                    },
                    {
                        type: "input",
                        name: "email",
                        label: t("Email"),
                        placeholder: t("Enter email address"),
                        required: true,
                        inputType: "email",
                    },
                    {
                        type: "input",
                        name: "password",
                        label: t("Password"),
                        placeholder: t("Leave blank to keep current password"),
                        required: false,
                        inputType: "password",
                    },
                ]}
                onSubmit={handleUpdate}
                submitLabel={t("Update")}
                cancelLabel={t("Cancel")}
            />

            {/* This is for change plan  */}
            <FormSheet
                open={planOpen}
                onOpenChange={setPlanOpen}
                title={t("Change Plan")}
                description={t("Update the subscription plan for this user")}
                form={planForm}
                fields={[
                    {
                        type: "select",
                        name: "plan_id",
                        label: t("Plan"),
                        placeholder: t("Select a plan"),
                        required: true,
                        searchable: true,
                        options: plans.map((plan) => ({
                            value: String(plan.id),
                            label: plan.name,
                        })),
                    },
                ]}
                onSubmit={handleChangePlanSubmit}
                submitLabel={t("Update Plan")}
                cancelLabel={t("Cancel")}
            />

            {selectedAction && currentDialog && (
                <ConfirmDialog
                    open={confirmOpen}
                    onOpenChange={setConfirmOpen}
                    icon={currentDialog.icon}
                    title={currentDialog.title}
                    description={currentDialog.description}
                    cancelLabel={t("Cancel")}
                    confirmLabel={currentDialog.confirmLabel}
                    onConfirm={handleAction}
                    loading={actionLoading}
                />
            )}
        </AppLayout>
    );
}
