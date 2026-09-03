import React from "react";
import { Head, router, useForm } from "@inertiajs/react";
import { useTranslation } from "react-i18next";
import { toast } from "sonner";
import { Button } from "@/components/ui/button";
import { LoadingSwap } from "@/components/ui/loading-swap";
import { Switch } from "@/components/ui/switch";
import AppLayout from "@/layouts/app/app-layout";
import FormInput from "@/components/admin/form-input";
import FormTextarea from "@/components/admin/form-textarea";
import { BreadcrumbItem } from "@/types";
import Heading from "@/components/heading";

interface CustomTemplate {
    id: number;
    unique_slug: string;
    name: string;
    title?: string;
}

interface Props {
    templates: CustomTemplate[];
}

interface AddPlanForm {
    name: string;
    description: string;
    price: string;
    validity: string;

    ai_credits: string;
    ai_image_credits: string;

    is_private: boolean;
    is_recommended: boolean;

    speech_to_text: boolean;
    text_to_speech: boolean;
    code_generator: boolean;
    personalized_chat: boolean;
    document_analyzer: boolean;
    site_analyzer: boolean;
    customer_support: boolean;

    [key: string]: string | boolean;
}

export default function AddPlan({ templates }: Props) {
    const { t } = useTranslation();

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: t("Dashboard"),
            href: route("dashboard.admin.overview"),
        },
        {
            title: t("Plans"),
            href: route("dashboard.admin.index.plans"),
        },
        {
            title: t("Add Plan"),
            href: "#",
        },
    ];

    const { data, setData, post, processing, errors, clearErrors, reset } =
        useForm<AddPlanForm>({
            name: "",
            description: "",
            price: "",
            validity: "",

            ai_credits: "",
            ai_credits_unit: "M",
            ai_image_credits: "",

            is_private: false,
            is_recommended: false,

            speech_to_text: false,
            text_to_speech: false,
            code_generator: false,
            personalized_chat: false,
            document_analyzer: false,
            site_analyzer: false,
            customer_support: false,
        });

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        post(route("dashboard.admin.save.plan"), {
            preserveScroll: true,

            onSuccess: () => {
                toast.success(t("Plan added successfully!"));
                reset();
            },

            onError: () => {
                toast.error(t("Please check the form for errors."));
            },
        });
    };

    const updateField = (
        field: Extract<keyof AddPlanForm, string>,
        value: string | boolean,
    ) => {
        setData(field, value);
        clearErrors(field);
    };

    const getTemplateTitle = (template: CustomTemplate) => {
        return template.title || template.name || template.unique_slug;
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Add Plan")} />

            <form onSubmit={submit} className="space-y-6">
                {/* Header */}
                <div>
                    <Heading
                        title={t("Add Plan")}
                        description={t("Create a new subscription plan.")}
                    />
                </div>

                {/* Basic Information */}
                <div className="space-y-5">
                    <h2 className="text-lg font-semibold">
                        {t("Basic Information")}
                    </h2>

                    <FormInput
                        id="name"
                        name="name"
                        type="text"
                        label={t("Plan Name")}
                        required
                        value={data.name}
                        placeholder={t("Enter plan name")}
                        error={errors.name}
                        disabled={processing}
                        onChange={(e) => updateField("name", e.target.value)}
                    />

                    <FormTextarea
                        id="description"
                        name="description"
                        label={t("Description")}
                        required
                        value={data.description}
                        placeholder={t("Enter plan description")}
                        error={errors.description}
                        disabled={processing}
                        onChange={(e) =>
                            updateField("description", e.target.value)
                        }
                    />
                </div>

                {/* Pricing */}
                <div className="space-y-5">
                    <h2 className="text-lg font-semibold">{t("Pricing")}</h2>

                    <div className="grid gap-5 md:grid-cols-2 items-start">
                        <FormInput
                            id="price"
                            name="price"
                            type="number"
                            label={t("Price")}
                            required
                            value={data.price}
                            placeholder={t("Enter plan price")}
                            error={errors.price}
                            disabled={processing}
                            onChange={(e) =>
                                updateField("price", e.target.value)
                            }
                        />

                        <FormInput
                            id="validity"
                            name="validity"
                            type="number"
                            label={t("Validity")}
                            required
                            value={data.validity}
                            placeholder={t("Enter validity in days")}
                            error={errors.validity}
                            disabled={processing}
                            onChange={(e) =>
                                updateField("validity", e.target.value)
                            }
                        />
                    </div>

                    <p className="text-sm text-muted-foreground">
                        {t(
                            "Use 31 for monthly, 366 for yearly, or 9999 for lifetime access.",
                        )}
                    </p>
                </div>

                {/* Credits */}
                <div className="space-y-5">
                    <h2 className="text-lg font-semibold">{t("Credits")}</h2>

                    <div className="grid gap-5 md:grid-cols-2 items-start">
                        <FormInput
                            id="ai_credits"
                            name="ai_credits"
                            type="number"
                            label={t("AI Credits")}
                            required
                            value={data.ai_credits}
                            placeholder={t("Enter AI credits")}
                            error={errors.ai_credits}
                            disabled={processing}
                            onChange={(e) =>
                                updateField("ai_credits", e.target.value)
                            }
                        />

                        <FormInput
                            id="ai_image_credits"
                            name="ai_image_credits"
                            type="number"
                            label={t("AI Image Credits")}
                            required
                            value={data.ai_image_credits}
                            placeholder={t("Enter AI image credits")}
                            error={errors.ai_image_credits}
                            disabled={processing}
                            onChange={(e) =>
                                updateField("ai_image_credits", e.target.value)
                            }
                        />
                    </div>
                </div>

                {/* Content Templates */}
                <div className="space-y-5">
                    <div>
                        <h2 className="text-lg font-semibold">
                            {t("Content Templates")}
                        </h2>

                        <p className="text-sm text-muted-foreground">
                            {t(
                                "Select the content templates available with this plan.",
                            )}
                        </p>
                    </div>

                    {templates.length > 0 ? (
                        <div className="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            {templates.map((template) => (
                                <label
                                    key={template.id}
                                    className="flex cursor-pointer items-center gap-3 rounded-md border p-3"
                                >
                                    <input
                                        type="checkbox"
                                        checked={
                                            data[template.unique_slug] === true
                                        }
                                        disabled={processing}
                                        onChange={(e) =>
                                            updateField(
                                                template.unique_slug,
                                                e.target.checked,
                                            )
                                        }
                                        className="h-4 w-4"
                                    />

                                    <span className="text-sm">
                                        {getTemplateTitle(template)}
                                    </span>
                                </label>
                            ))}
                        </div>
                    ) : (
                        <p className="text-sm text-muted-foreground">
                            {t("No templates available.")}
                        </p>
                    )}
                </div>

                {/* AI Features */}
                <div className="space-y-5">
                    <div>
                        <h2 className="text-lg font-semibold">
                            {t("AI Features")}
                        </h2>

                        <p className="text-sm text-muted-foreground">
                            {t(
                                "Choose the AI features available for this plan.",
                            )}
                        </p>
                    </div>

                    <div className="divide-y rounded-md border">
                        <FeatureSwitch
                            label={t("Speech to Text")}
                            description={t(
                                "Allow users to convert speech into text.",
                            )}
                            checked={data.speech_to_text}
                            disabled={processing}
                            onCheckedChange={(value) =>
                                updateField("speech_to_text", value)
                            }
                        />

                        <FeatureSwitch
                            label={t("Text to Speech")}
                            description={t(
                                "Allow users to convert text into speech.",
                            )}
                            checked={data.text_to_speech}
                            disabled={processing}
                            onCheckedChange={(value) =>
                                updateField("text_to_speech", value)
                            }
                        />

                        <FeatureSwitch
                            label={t("Code Generator")}
                            description={t("Allow users to generate code.")}
                            checked={data.code_generator}
                            disabled={processing}
                            onCheckedChange={(value) =>
                                updateField("code_generator", value)
                            }
                        />

                        <FeatureSwitch
                            label={t("Personalized Chat")}
                            description={t(
                                "Allow users to use personalized chat.",
                            )}
                            checked={data.personalized_chat}
                            disabled={processing}
                            onCheckedChange={(value) =>
                                updateField("personalized_chat", value)
                            }
                        />

                        <FeatureSwitch
                            label={t("Document Analyzer")}
                            description={t("Allow users to analyze documents.")}
                            checked={data.document_analyzer}
                            disabled={processing}
                            onCheckedChange={(value) =>
                                updateField("document_analyzer", value)
                            }
                        />

                        <FeatureSwitch
                            label={t("Site Analyzer")}
                            description={t("Allow users to analyze websites.")}
                            checked={data.site_analyzer}
                            disabled={processing}
                            onCheckedChange={(value) =>
                                updateField("site_analyzer", value)
                            }
                        />

                        <FeatureSwitch
                            label={t("Customer Support")}
                            description={t(
                                "Include customer support with this plan.",
                            )}
                            checked={data.customer_support}
                            disabled={processing}
                            onCheckedChange={(value) =>
                                updateField("customer_support", value)
                            }
                        />
                    </div>
                </div>

                {/* Plan Settings */}
                <div className="space-y-5">
                    <h2 className="text-lg font-semibold">
                        {t("Plan Settings")}
                    </h2>

                    <div className="divide-y rounded-md border">
                        <FeatureSwitch
                            label={t("Recommended")}
                            description={t(
                                "Mark this plan as the recommended plan.",
                            )}
                            checked={data.is_recommended}
                            disabled={processing}
                            onCheckedChange={(value) =>
                                updateField("is_recommended", value)
                            }
                        />

                        <FeatureSwitch
                            label={t("Private Plan")}
                            description={t(
                                "Make this plan available only to selected users.",
                            )}
                            checked={data.is_private}
                            disabled={processing}
                            onCheckedChange={(value) =>
                                updateField("is_private", value)
                            }
                        />
                    </div>
                </div>

                {/* Submit */}
                <div className="flex justify-end gap-3">
                    <Button
                        type="button"
                        variant="outline"
                        disabled={processing}
                        onClick={() => router.get(route("admin.index.plans"))}
                    >
                        {t("Cancel")}
                    </Button>

                    <Button type="submit" disabled={processing}>
                        <LoadingSwap isLoading={processing}>
                            {t("Add Plan")}
                        </LoadingSwap>
                    </Button>
                </div>
            </form>
        </AppLayout>
    );
}

interface FeatureSwitchProps {
    label: string;
    description?: string;
    checked: boolean;
    disabled?: boolean;
    onCheckedChange: (checked: boolean) => void;
}

function FeatureSwitch({
    label,
    description,
    checked,
    disabled,
    onCheckedChange,
}: FeatureSwitchProps) {
    return (
        <div className="flex items-center justify-between gap-4 p-4">
            <div className="space-y-1">
                <p className="text-sm font-medium">{label}</p>

                {description && (
                    <p className="text-sm text-muted-foreground">
                        {description}
                    </p>
                )}
            </div>

            <Switch
                checked={checked}
                disabled={disabled}
                onCheckedChange={onCheckedChange}
            />
        </div>
    );
}
