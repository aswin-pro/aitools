import { useForm } from "@inertiajs/react";
import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import FormInput from "@/components/admin/form-input";
import { toast } from "sonner";
import { useTranslation } from "react-i18next";
import { LoadingSwap } from "@/components/ui/loading-swap";
import { Switch } from "@/components/ui/switch";

interface MSG91WhatsappTemplate {
    id: number;
    template_name: string;
    template_id: string | null;
    namespace: string | null;
    variables: string | null;
    is_enabled: number;
}

interface Props {
    adminTemplate?: MSG91WhatsappTemplate;
    userTemplate?: MSG91WhatsappTemplate;
}

interface VariableRow {
    checked: boolean;
    value: string;
}

const variableOptions = [
    {
        value: "app_name",
        label: "App Name",
    },
    {
        value: "name",
        label: "User Name",
    },
    {
        value: "email",
        label: "User Email",
    },
];

const parseVariables = (
    variables: string | null | undefined,
): string[] => {
    if (!variables) {
        return [];
    }

    try {
        const parsed = JSON.parse(variables);

        return Array.isArray(parsed) ? parsed : [];
    } catch {
        return [];
    }
};

const createVariableRows = (
    variables: string | null | undefined,
): VariableRow[] => {
    const savedVariables = parseVariables(variables);

    return [
        {
            checked: Boolean(savedVariables[0]),
            value: savedVariables[0] || "app_name",
        },
        {
            checked: Boolean(savedVariables[1]),
            value: savedVariables[1] || "app_name",
        },
        {
            checked: Boolean(savedVariables[2]),
            value: savedVariables[2] || "app_name",
        },
    ];
};

export default function PlanRenewal({
    adminTemplate,
    userTemplate,
}: Props) {
    const { t } = useTranslation();

    const [adminVariableRows, setAdminVariableRows] =
        useState<VariableRow[]>(
            createVariableRows(adminTemplate?.variables),
        );

    const [userVariableRows, setUserVariableRows] =
        useState<VariableRow[]>(
            createVariableRows(userTemplate?.variables),
        );

    const form = useForm({
        plan_renewal_admin: adminTemplate?.is_enabled === 1,
        plan_renewal_admin_template_id:
            adminTemplate?.template_id ?? "",
        plan_renewal_admin_template_namespace:
            adminTemplate?.namespace ?? "",

        plan_renewal_user: userTemplate?.is_enabled === 1,
        plan_renewal_user_template_id:
            userTemplate?.template_id ?? "",
        plan_renewal_user_template_namespace:
            userTemplate?.namespace ?? "",

        variablesAdmin: parseVariables(adminTemplate?.variables),
        variablesUser: parseVariables(userTemplate?.variables),
    });

    const updateAdminVariables = (rows: VariableRow[]) => {
        setAdminVariableRows(rows);

        form.setData(
            "variablesAdmin",
            rows
                .filter((row) => row.checked)
                .map((row) => row.value),
        );
    };

    const updateUserVariables = (rows: VariableRow[]) => {
        setUserVariableRows(rows);

        form.setData(
            "variablesUser",
            rows
                .filter((row) => row.checked)
                .map((row) => row.value),
        );
    };

    const handleAdminCheckboxChange = (
        index: number,
        checked: boolean,
    ) => {
        const rows = [...adminVariableRows];

        rows[index] = {
            ...rows[index],
            checked,
        };

        updateAdminVariables(rows);
    };

    const handleAdminVariableChange = (
        index: number,
        value: string,
    ) => {
        const rows = [...adminVariableRows];

        rows[index] = {
            ...rows[index],
            value,
        };

        updateAdminVariables(rows);
    };

    const handleUserCheckboxChange = (
        index: number,
        checked: boolean,
    ) => {
        const rows = [...userVariableRows];

        rows[index] = {
            ...rows[index],
            checked,
        };

        updateUserVariables(rows);
    };

    const handleUserVariableChange = (
        index: number,
        value: string,
    ) => {
        const rows = [...userVariableRows];

        rows[index] = {
            ...rows[index],
            value,
        };

        updateUserVariables(rows);
    };

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        form.post(
            route(
                "admin.msg91_whatsapp_template_plan_renewal.update",
            ),
            {
                preserveScroll: true,

                onSuccess: () => {
                    toast.success(
                        t("Template updated successfully."),
                    );
                },

                onError: () => {
                    toast.error(
                        t("Failed to update template!"),
                    );
                },
            },
        );
    };

    return (
        <section className="space-y-6">
            <div>
                <h2 className="text-lg font-semibold">
                    {t("Plan Renewal Notification")}
                </h2>
            </div>

            <form onSubmit={submit} className="space-y-10">
                {/* Admin */}
                <div className="space-y-6">
                    <h3 className="text-base font-semibold">
                        {t("Admin")}
                    </h3>

                    <div className="grid gap-6 md:grid-cols-3">
                        {/* Enable Admin */}
                        <div className="grid gap-2">
                            <Label
                                htmlFor="plan_renewal_admin"
                                required
                            >
                                {t("Send Notification to Admin")}
                            </Label>

                            <div className="flex h-10 items-center">
                                <Switch
                                    id="plan_renewal_admin"
                                    checked={
                                        form.data.plan_renewal_admin
                                    }
                                    onCheckedChange={(checked) => {
                                        form.setData(
                                            "plan_renewal_admin",
                                            checked,
                                        );

                                        form.clearErrors(
                                            "plan_renewal_admin_template_id",
                                            "plan_renewal_admin_template_namespace",
                                        );
                                    }}
                                />
                            </div>
                        </div>

                        {/* Admin Template ID */}
                        <FormInput
                            id="plan_renewal_admin_template_id"
                            name="plan_renewal_admin_template_id"
                            label={t("Admin Template Name")}
                            placeholder={t("Template Name")}
                            required
                            value={
                                form.data
                                    .plan_renewal_admin_template_id
                            }
                            onChange={(e) => {
                                form.setData(
                                    "plan_renewal_admin_template_id",
                                    e.target.value,
                                );

                                form.clearErrors(
                                    "plan_renewal_admin_template_id",
                                );
                            }}
                            error={
                                form.errors
                                    .plan_renewal_admin_template_id
                            }
                        />

                        {/* Admin Namespace */}
                        <FormInput
                            id="plan_renewal_admin_template_namespace"
                            name="plan_renewal_admin_template_namespace"
                            label={t("Template Namespace")}
                            placeholder={t("Template Namespace")}
                            required
                            value={
                                form.data
                                    .plan_renewal_admin_template_namespace
                            }
                            onChange={(e) => {
                                form.setData(
                                    "plan_renewal_admin_template_namespace",
                                    e.target.value,
                                );

                                form.clearErrors(
                                    "plan_renewal_admin_template_namespace",
                                );
                            }}
                            error={
                                form.errors
                                    .plan_renewal_admin_template_namespace
                            }
                        />
                    </div>

                    {/* Admin Variables */}
                    <div className="space-y-3">
                        <h4 className="text-sm font-semibold">
                            {t("Variables Admin")}
                        </h4>

                        <div className="overflow-x-auto rounded-md border">
                            <table className="w-full text-sm">
                                <thead>
                                    <tr className="border-b bg-muted/50">
                                        <th className="px-4 py-3 text-left font-medium">
                                            {t("Variable")}
                                        </th>

                                        <th className="px-4 py-3 text-left font-medium">
                                            {t("Select Variable")}
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    {adminVariableRows.map(
                                        (row, index) => (
                                            <tr
                                                key={index}
                                                className="border-b last:border-0"
                                            >
                                                <td className="px-4 py-3">
                                                    <div className="flex items-center gap-3">
                                                        <input
                                                            type="checkbox"
                                                            checked={
                                                                row.checked
                                                            }
                                                            onChange={(e) =>
                                                                handleAdminCheckboxChange(
                                                                    index,
                                                                    e.target
                                                                        .checked,
                                                                )
                                                            }
                                                            className="h-4 w-4 rounded border-input"
                                                        />

                                                        <span>
                                                            {"@{{ "}
                                                            {index + 1}
                                                            {" }}"}
                                                        </span>
                                                    </div>
                                                </td>

                                                <td className="px-4 py-3">
                                                    <select
                                                        value={
                                                            row.value
                                                        }
                                                        onChange={(e) =>
                                                            handleAdminVariableChange(
                                                                index,
                                                                e.target
                                                                    .value,
                                                            )
                                                        }
                                                        className="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                                                    >
                                                        {variableOptions.map(
                                                            (
                                                                option,
                                                            ) => (
                                                                <option
                                                                    key={
                                                                        option.value
                                                                    }
                                                                    value={
                                                                        option.value
                                                                    }
                                                                >
                                                                    {t(
                                                                        option.label,
                                                                    )}
                                                                </option>
                                                            ),
                                                        )}
                                                    </select>
                                                </td>
                                            </tr>
                                        ),
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {/* User */}
                <div className="space-y-6">
                    <h3 className="text-base font-semibold">
                        {t("User")}
                    </h3>

                    <div className="grid gap-6 md:grid-cols-3">
                        {/* Enable User */}
                        <div className="grid gap-2">
                            <Label
                                htmlFor="plan_renewal_user"
                                required
                            >
                                {t("Send Notification to User")}
                            </Label>

                            <div className="flex h-10 items-center">
                                <Switch
                                    id="plan_renewal_user"
                                    checked={
                                        form.data.plan_renewal_user
                                    }
                                    onCheckedChange={(checked) => {
                                        form.setData(
                                            "plan_renewal_user",
                                            checked,
                                        );

                                        form.clearErrors(
                                            "plan_renewal_user_template_id",
                                            "plan_renewal_user_template_namespace",
                                        );
                                    }}
                                />
                            </div>
                        </div>

                        {/* User Template ID */}
                        <FormInput
                            id="plan_renewal_user_template_id"
                            name="plan_renewal_user_template_id"
                            label={t("User Template Name")}
                            placeholder={t("Template Name")}
                            required
                            value={
                                form.data
                                    .plan_renewal_user_template_id
                            }
                            onChange={(e) => {
                                form.setData(
                                    "plan_renewal_user_template_id",
                                    e.target.value,
                                );

                                form.clearErrors(
                                    "plan_renewal_user_template_id",
                                );
                            }}
                            error={
                                form.errors
                                    .plan_renewal_user_template_id
                            }
                        />

                        {/* User Namespace */}
                        <FormInput
                            id="plan_renewal_user_template_namespace"
                            name="plan_renewal_user_template_namespace"
                            label={t("Template Namespace")}
                            placeholder={t("Template Namespace")}
                            required
                            value={
                                form.data
                                    .plan_renewal_user_template_namespace
                            }
                            onChange={(e) => {
                                form.setData(
                                    "plan_renewal_user_template_namespace",
                                    e.target.value,
                                );

                                form.clearErrors(
                                    "plan_renewal_user_template_namespace",
                                );
                            }}
                            error={
                                form.errors
                                    .plan_renewal_user_template_namespace
                            }
                        />
                    </div>

                    {/* User Variables */}
                    <div className="space-y-3">
                        <h4 className="text-sm font-semibold">
                            {t("Variables User")}
                        </h4>

                        <div className="overflow-x-auto rounded-md border">
                            <table className="w-full text-sm">
                                <thead>
                                    <tr className="border-b bg-muted/50">
                                        <th className="px-4 py-3 text-left font-medium">
                                            {t("Variable")}
                                        </th>

                                        <th className="px-4 py-3 text-left font-medium">
                                            {t("Select Variable")}
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    {userVariableRows.map(
                                        (row, index) => (
                                            <tr
                                                key={index}
                                                className="border-b last:border-0"
                                            >
                                                <td className="px-4 py-3">
                                                    <div className="flex items-center gap-3">
                                                        <input
                                                            type="checkbox"
                                                            checked={
                                                                row.checked
                                                            }
                                                            onChange={(e) =>
                                                                handleUserCheckboxChange(
                                                                    index,
                                                                    e.target
                                                                        .checked,
                                                                )
                                                            }
                                                            className="h-4 w-4 rounded border-input"
                                                        />

                                                        <span>
                                                            {"@{{ "}
                                                            {index + 1}
                                                            {" }}"}
                                                        </span>
                                                    </div>
                                                </td>

                                                <td className="px-4 py-3">
                                                    <select
                                                        value={
                                                            row.value
                                                        }
                                                        onChange={(e) =>
                                                            handleUserVariableChange(
                                                                index,
                                                                e.target
                                                                    .value,
                                                            )
                                                        }
                                                        className="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                                                    >
                                                        {variableOptions.map(
                                                            (
                                                                option,
                                                            ) => (
                                                                <option
                                                                    key={
                                                                        option.value
                                                                    }
                                                                    value={
                                                                        option.value
                                                                    }
                                                                >
                                                                    {t(
                                                                        option.label,
                                                                    )}
                                                                </option>
                                                            ),
                                                        )}
                                                    </select>
                                                </td>
                                            </tr>
                                        ),
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div className="flex justify-end">
                    <Button
                        type="submit"
                        disabled={form.processing}
                    >
                        <LoadingSwap isLoading={form.processing}>
                            {t("Update")}
                        </LoadingSwap>
                    </Button>
                </div>
            </form>
        </section>
    );
}