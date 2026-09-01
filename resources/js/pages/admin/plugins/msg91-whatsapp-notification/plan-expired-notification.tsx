import { useForm } from "@inertiajs/react";
import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import FormInput from "@/components/admin/form-input";
import { LoadingSwap } from "@/components/ui/loading-swap";
import { Switch } from "@/components/ui/switch";
import { toast } from "sonner";
import { useTranslation } from "react-i18next";

interface MSG91WhatsappTemplate {
    id: number;
    template_name: string;
    template_id: string | null;
    namespace: string | null;
    variables: string | null;
    is_enabled: number;
}

interface Props {
    template?: MSG91WhatsappTemplate;
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

export default function PlanExpiredNotification({
    template,
}: Props) {
    const { t } = useTranslation();

    const [variableRows, setVariableRows] =
        useState<VariableRow[]>(
            createVariableRows(template?.variables),
        );

    const form = useForm({
        user_plan_expired_notification:
            template?.is_enabled === 1,

        user_plan_expired_notification_template_id:
            template?.template_id ?? "",

        user_plan_expired_notification_template_namespace:
            template?.namespace ?? "",

        variables: parseVariables(template?.variables),
    });

    const updateVariables = (rows: VariableRow[]) => {
        setVariableRows(rows);

        form.setData(
            "variables",
            rows
                .filter((row) => row.checked)
                .map((row) => row.value),
        );
    };

    const handleCheckboxChange = (
        index: number,
        checked: boolean,
    ) => {
        const rows = [...variableRows];

        rows[index] = {
            ...rows[index],
            checked,
        };

        updateVariables(rows);
    };

    const handleVariableChange = (
        index: number,
        value: string,
    ) => {
        const rows = [...variableRows];

        rows[index] = {
            ...rows[index],
            value,
        };

        updateVariables(rows);
    };

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        form.post(
            route(
                "admin.msg91_whatsapp_template_user_plan_expired_notification.update",
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
                    {t("User Plan Expired Notification")}
                </h2>
            </div>

            <form onSubmit={submit} className="space-y-10">
                <div className="space-y-6">
                    <h3 className="text-base font-semibold">
                        {t("Notification")}
                    </h3>

                    <div className="grid gap-6 md:grid-cols-3">
                        {/* Enable Notification */}
                        <div className="grid gap-2">
                            <Label
                                htmlFor="user_plan_expired_notification"
                                required
                            >
                                {t("Send Notification")}
                            </Label>

                            <div className="flex h-10 items-center">
                                <Switch
                                    id="user_plan_expired_notification"
                                    checked={
                                        form.data
                                            .user_plan_expired_notification
                                    }
                                    onCheckedChange={(checked) => {
                                        form.setData(
                                            "user_plan_expired_notification",
                                            checked,
                                        );

                                        form.clearErrors(
                                            "user_plan_expired_notification_template_id",
                                            "user_plan_expired_notification_template_namespace",
                                        );
                                    }}
                                />
                            </div>
                        </div>

                        {/* Template ID */}
                        <FormInput
                            id="user_plan_expired_notification_template_id"
                            name="user_plan_expired_notification_template_id"
                            label={t("Template Name")}
                            placeholder={t("Template Name")}
                            required
                            value={
                                form.data
                                    .user_plan_expired_notification_template_id
                            }
                            onChange={(e) => {
                                form.setData(
                                    "user_plan_expired_notification_template_id",
                                    e.target.value,
                                );

                                form.clearErrors(
                                    "user_plan_expired_notification_template_id",
                                );
                            }}
                            error={
                                form.errors
                                    .user_plan_expired_notification_template_id
                            }
                        />

                        {/* Namespace */}
                        <FormInput
                            id="user_plan_expired_notification_template_namespace"
                            name="user_plan_expired_notification_template_namespace"
                            label={t("Template Namespace")}
                            placeholder={t("Template Namespace")}
                            required
                            value={
                                form.data
                                    .user_plan_expired_notification_template_namespace
                            }
                            onChange={(e) => {
                                form.setData(
                                    "user_plan_expired_notification_template_namespace",
                                    e.target.value,
                                );

                                form.clearErrors(
                                    "user_plan_expired_notification_template_namespace",
                                );
                            }}
                            error={
                                form.errors
                                    .user_plan_expired_notification_template_namespace
                            }
                        />
                    </div>

                    {/* Variables */}
                    <div className="space-y-3">
                        <h4 className="text-sm font-semibold">
                            {t("Variables")}
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
                                    {variableRows.map(
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
                                                                handleCheckboxChange(
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
                                                            handleVariableChange(
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