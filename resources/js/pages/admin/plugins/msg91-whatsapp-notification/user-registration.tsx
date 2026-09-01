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

interface VariableRow {
    checked: boolean;
    value: string;
}

interface UserRegistrationProps {
    template?: MSG91WhatsappTemplate;
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

const parseVariables = (variables: string | null | undefined): string[] => {
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

export default function UserRegistration({
    template,
}: UserRegistrationProps) {
    const { t } = useTranslation();

    const savedVariables = parseVariables(template?.variables);

    const [variableRows, setVariableRows] = useState<VariableRow[]>([
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
    ]);

    const form = useForm({
        new_user_registration_admin: template?.is_enabled === 1,
        new_user_registration_admin_template_id:
            template?.template_id || "",
        new_user_registration_admin_template_namespace:
            template?.namespace || "",
        variables: savedVariables,
    });

    const updateVariables = (rows: VariableRow[]) => {
        setVariableRows(rows);

        form.setData(
            "variables",
            rows.filter((row) => row.checked).map((row) => row.value),
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
                "admin.msg91_whatsapp_template_user_register.update",
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
                    {t("New User Registration Notification")}
                </h2>
            </div>

            <form onSubmit={submit} className="space-y-6">
                <div className="grid gap-6 md:grid-cols-3">
<div className="grid gap-2">
    <Label
        htmlFor="new_user_registration_admin"
        required
    >
        {t("Enable")}
    </Label>

    <div className="flex h-10 items-center">
        <Switch
            id="new_user_registration_admin"
            checked={form.data.new_user_registration_admin}
            onCheckedChange={(checked) => {
                form.setData(
                    "new_user_registration_admin",
                    checked,
                );

                form.clearErrors(
                    "new_user_registration_admin_template_id",
                    "new_user_registration_admin_template_namespace",
                );
            }}
        />
    </div>
</div>

                    {/* Template ID */}
                    <FormInput
                        id="new_user_registration_admin_template_id"
                        name="new_user_registration_admin_template_id"
                        label={t("Admin Template Name")}
                        placeholder={t("Template Name")}
                        required
                        value={
                            form.data
                                .new_user_registration_admin_template_id
                        }
                        onChange={(e) =>
                            form.setData(
                                "new_user_registration_admin_template_id",
                                e.target.value,
                            )
                        }
                        error={
                            form.errors
                                .new_user_registration_admin_template_id
                        }
                    />

                    {/* Namespace */}
                    <FormInput
                        id="new_user_registration_admin_template_namespace"
                        name="new_user_registration_admin_template_namespace"
                        label={t("Template Namespace")}
                        placeholder={t("Template Namespace")}
                        required
                        value={
                            form.data
                                .new_user_registration_admin_template_namespace
                        }
                        onChange={(e) =>
                            form.setData(
                                "new_user_registration_admin_template_namespace",
                                e.target.value,
                            )
                        }
                        error={
                            form.errors
                                .new_user_registration_admin_template_namespace
                        }
                    />
                </div>

                {/* Variables Admin */}
                <div className="space-y-3">
                    <h3 className="text-sm font-semibold">
                        {t("Variables Admin")}
                    </h3>

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
                                {variableRows.map((row, index) => (
                                    <tr
                                        key={index}
                                        className="border-b last:border-0"
                                    >
                                        <td className="px-4 py-3">
                                            <div className="flex items-center gap-3">
                                                <input
                                                    type="checkbox"
                                                    checked={row.checked}
                                                    onChange={(e) =>
                                                        handleCheckboxChange(
                                                            index,
                                                            e.target.checked,
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
                                                value={row.value}
                                                onChange={(e) =>
                                                    handleVariableChange(
                                                        index,
                                                        e.target.value,
                                                    )
                                                }
                                                className="h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                                            >
                                                {variableOptions.map(
                                                    (option) => (
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
                                ))}
                            </tbody>
                        </table>
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