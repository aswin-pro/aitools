import { Button } from "@/components/ui/button";
import Heading from "@/components/heading";
import { CustomTemplateCategory } from "@/types/admin";
import { Head, router, useForm } from "@inertiajs/react";
import { Plus, Trash2 } from "lucide-react";
import { useRef } from "react";
import { useTranslation } from "react-i18next";
import { toast } from "sonner";
import { LoadingSwap } from "@/components/ui/loading-swap";
import AppLayout from "@/layouts/app/app-layout";
import { BreadcrumbItem } from "@/types";
import { SearchableSelect } from "@/components/admin/searchable-select";
import FormInput from "@/components/admin/form-input";
import FormTextarea from "@/components/admin/form-textarea";
import { Card, CardContent } from "@/components/ui/card";

interface EditTemplateProps {
    template: {
        id: number;
        category_id: number;
        name: string;
        description: string;
        prompt: string;
    };
    fields: TemplateField[];
    categories: CustomTemplateCategory[];
}

interface TemplateField {
    id?: number;
    ai_input: string;
    field_type: string;
    field_name: string;
    field_description: string;
}

interface TemplateFormData {
    template_id: string;
    category_id: string;
    name: string;
    description: string;
    aiInput: string[];
    fieldType: string[];
    fieldTitle: string[];
    fieldDescription: string[];
    prompt: string;
}

export default function Edit({
    template,
    fields,
    categories,
}: EditTemplateProps) {
    const { t } = useTranslation();

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: t("Dashboard"),
            href: route("dashboard.admin.overview"),
        },
        {
            title: t("Content Templates"),
            href: route("dashboard.admin.templates"),
        },
        {
            title: t("Edit Template"),
            href: "#",
        },
    ];

    const promptRef = useRef<HTMLTextAreaElement>(null);

    /*
     * Convert existing template fields from Laravel format
     * to the same format used by the Create page.
     */
    const initialAiInput =
        fields.length > 0
            ? fields.map((field) => field.ai_input)
            : ["##input1##"];

    const initialFieldType =
        fields.length > 0
            ? fields.map((field) => field.field_type)
            : ["text"];

    const initialFieldTitle =
        fields.length > 0
            ? fields.map((field) => field.field_name)
            : [""];

    const initialFieldDescription =
        fields.length > 0
            ? fields.map((field) => field.field_description)
            : [""];

    const {
        data,
        setData,
        post,
        processing,
        errors,
        clearErrors,
    } = useForm<TemplateFormData>({
        template_id: String(template.id),
        category_id: String(template.category_id),
        name: template.name ?? "",
        description: template.description ?? "",
        aiInput: initialAiInput,
        fieldType: initialFieldType,
        fieldTitle: initialFieldTitle,
        fieldDescription: initialFieldDescription,
        prompt: template.prompt ?? "",
    });

    const promptVariables = [
        {
            label: "Number of results",
            value: "##results##",
        },
        {
            label: "Tone",
            value: "##tone##",
        },
        {
            label: "Language",
            value: "##lang##",
        },
    ];

    const insertVariable = (variable: string) => {
        const textarea = promptRef.current;

        if (!textarea) {
            return;
        }

        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;

        const newValue =
            data.prompt.substring(0, start) +
            variable +
            data.prompt.substring(end);

        setData("prompt", newValue);
        clearErrors("prompt");

        requestAnimationFrame(() => {
            textarea.focus();

            const cursorPosition = start + variable.length;

            textarea.setSelectionRange(
                cursorPosition,
                cursorPosition,
            );
        });
    };

    const addField = () => {
        /*
         * Find the next input number based on the current
         * number of fields.
         */
        const nextNumber = data.aiInput.length + 1;

        setData({
            ...data,
            aiInput: [
                ...data.aiInput,
                `##input${nextNumber}##`,
            ],
            fieldType: [
                ...data.fieldType,
                "text",
            ],
            fieldTitle: [
                ...data.fieldTitle,
                "",
            ],
            fieldDescription: [
                ...data.fieldDescription,
                "",
            ],
        });
    };

    const removeField = (index: number) => {
        /*
         * Keep the first field.
         */
        if (index === 0) {
            return;
        }

        setData({
            ...data,
            aiInput: data.aiInput.filter(
                (_, i) => i !== index,
            ),
            fieldType: data.fieldType.filter(
                (_, i) => i !== index,
            ),
            fieldTitle: data.fieldTitle.filter(
                (_, i) => i !== index,
            ),
            fieldDescription: data.fieldDescription.filter(
                (_, i) => i !== index,
            ),
        });
    };

    const submit = (
        e: React.FormEvent<HTMLFormElement>,
    ) => {
        e.preventDefault();

        post(
            route("dashboard.admin.update.template"),
            {
                preserveScroll: true,

                onSuccess: () => {
                    toast.success(
                        t(
                            "Template Details Updated Successfully!",
                        ),
                    );

                    router.get(
                        route(
                            "dashboard.admin.templates",
                        ),
                    );
                },

                onError: () => {
                    toast.error(
                        t(
                            "Please fix the errors and try again.",
                        ),
                    );
                },
            },
        );
    };

    const categoryOptions = categories.map(
        (category) => ({
            value: String(category.id),
            label: category.category_name,
        }),
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("Edit Template")} />

            <div className="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
                <Heading
                    title={t("Edit Template")}
                    description={t(
                        "Update the template details",
                    )}
                />

                <form
                    onSubmit={submit}
                    className="space-y-8"
                >
                    <Card>
                        <CardContent className="space-y-6 p-5">
                            {/* Template Details */}
                            <div className="space-y-6">
                                <div>
                                    <h2 className="text-lg font-semibold">
                                        {t("Template Details")}
                                    </h2>

                                    <p className="text-sm text-muted-foreground">
                                        {t(
                                            "Update the basic details for your template.",
                                        )}
                                    </p>
                                </div>

                                <div className="grid items-start gap-6 md:grid-cols-2">
                                    <SearchableSelect
                                        name="category_id"
                                        label={t("Category")}
                                        value={
                                            data.category_id
                                        }
                                        options={
                                            categoryOptions
                                        }
                                        placeholder={t(
                                            "Select category",
                                        )}
                                        searchable
                                        error={
                                            errors.category_id
                                        }
                                        onChange={(
                                            value,
                                        ) => {
                                            setData(
                                                "category_id",
                                                value,
                                            );

                                            clearErrors(
                                                "category_id",
                                            );
                                        }}
                                    />

                                    <FormInput
                                        id="name"
                                        name="name"
                                        label={t("Name")}
                                        value={data.name}
                                        placeholder={t(
                                            "Enter template name",
                                        )}
                                        required
                                        error={errors.name}
                                        disabled={
                                            processing
                                        }
                                        onChange={(e) => {
                                            setData(
                                                "name",
                                                e.target.value,
                                            );

                                            clearErrors(
                                                "name",
                                            );
                                        }}
                                    />

                                    <div className="md:col-span-2">
                                        <FormTextarea
                                            id="description"
                                            name="description"
                                            label={t(
                                                "Description",
                                            )}
                                            value={
                                                data.description
                                            }
                                            placeholder={t(
                                                "Enter template description",
                                            )}
                                            required
                                            error={
                                                errors.description
                                            }
                                            disabled={
                                                processing
                                            }
                                            onChange={(e) => {
                                                setData(
                                                    "description",
                                                    e.target.value,
                                                );

                                                clearErrors(
                                                    "description",
                                                );
                                            }}
                                        />
                                    </div>
                                </div>
                            </div>

                            {/* Template Fields */}
                            <div className="space-y-6">
                                <div className="flex items-center justify-between gap-4">
                                    <div>
                                        <h2 className="text-lg font-semibold">
                                            {t(
                                                "Template Fields",
                                            )}
                                        </h2>

                                        <p className="text-sm text-muted-foreground">
                                            {t(
                                                "Define the fields that will be available in this template.",
                                            )}
                                        </p>
                                    </div>

                                    <Button
                                        type="button"
                                        onClick={addField}
                                        disabled={processing}
                                    >
                                        <Plus className="mr-2 size-4" />
                                        {t(
                                            "Add New Field",
                                        )}
                                    </Button>
                                </div>

                                <div className="space-y-6">
                                    {data.aiInput.map(
                                        (
                                            aiInput,
                                            index,
                                        ) => (
                                            <div
                                                key={index}
                                                className="space-y-5 rounded-lg border p-5"
                                            >
                                                <div className="flex items-center justify-between">
                                                    <h3 className="font-medium">
                                                        {t(
                                                            "Field",
                                                        )}{" "}
                                                        {index +
                                                            1}
                                                    </h3>

                                                    {index !==
                                                        0 && (
                                                        <Button
                                                            type="button"
                                                            variant="ghost"
                                                            size="icon"
                                                            onClick={() =>
                                                                removeField(
                                                                    index,
                                                                )
                                                            }
                                                            disabled={
                                                                processing
                                                            }
                                                        >
                                                            <Trash2 className="size-4 text-destructive" />
                                                        </Button>
                                                    )}
                                                </div>

                                                <div className="grid items-start gap-5 md:grid-cols-2">
                                                    {/* AI Input */}
                                                    <FormInput
                                                        id={`aiInput-${index}`}
                                                        name={`aiInput-${index}`}
                                                        label={t(
                                                            "AI Input",
                                                        )}
                                                        value={
                                                            aiInput
                                                        }
                                                        readOnly
                                                        disabled
                                                    />

                                                    {/* Field Type */}
                                                    <SearchableSelect
                                                        name={`fieldType-${index}`}
                                                        label={t(
                                                            "Field Type",
                                                        )}
                                                        value={
                                                            data
                                                                .fieldType[
                                                                index
                                                            ]
                                                        }
                                                        options={[
                                                            {
                                                                value: "text",
                                                                label: t(
                                                                    "Text",
                                                                ),
                                                            },
                                                            {
                                                                value: "textarea",
                                                                label: t(
                                                                    "Textarea",
                                                                ),
                                                            },
                                                            ...(index ===
                                                            0
                                                                ? [
                                                                      {
                                                                          value: "url",
                                                                          label: t(
                                                                              "URL",
                                                                          ),
                                                                      },
                                                                  ]
                                                                : []),
                                                        ]}
                                                        placeholder={t(
                                                            "Select field type",
                                                        )}
                                                        searchable={
                                                            false
                                                        }
                                                        error={
                                                            errors[
                                                                `fieldType.${index}`
                                                            ]
                                                        }
                                                        onChange={(
                                                            value,
                                                        ) => {
                                                            const fieldTypes =
                                                                [
                                                                    ...data.fieldType,
                                                                ];

                                                            fieldTypes[
                                                                index
                                                            ] =
                                                                value;

                                                            setData(
                                                                "fieldType",
                                                                fieldTypes,
                                                            );

                                                            clearErrors(
                                                                `fieldType.${index}`,
                                                            );
                                                        }}
                                                    />

                                                    {/* Field Title */}
                                                    <FormInput
                                                        id={`fieldTitle-${index}`}
                                                        name={`fieldTitle-${index}`}
                                                        label={t(
                                                            "Field Title",
                                                        )}
                                                        value={
                                                            data
                                                                .fieldTitle[
                                                                index
                                                            ]
                                                        }
                                                        placeholder={t(
                                                            "Enter field title",
                                                        )}
                                                        required
                                                        error={
                                                            errors[
                                                                `fieldTitle.${index}`
                                                            ]
                                                        }
                                                        disabled={
                                                            processing
                                                        }
                                                        onChange={(
                                                            e,
                                                        ) => {
                                                            const titles =
                                                                [
                                                                    ...data.fieldTitle,
                                                                ];

                                                            titles[
                                                                index
                                                            ] =
                                                                e.target.value;

                                                            setData(
                                                                "fieldTitle",
                                                                titles,
                                                            );

                                                            clearErrors(
                                                                `fieldTitle.${index}`,
                                                            );
                                                        }}
                                                    />

                                                    {/* Field Description */}
                                                    <FormInput
                                                        id={`fieldDescription-${index}`}
                                                        name={`fieldDescription-${index}`}
                                                        label={t(
                                                            "Field Description",
                                                        )}
                                                        value={
                                                            data
                                                                .fieldDescription[
                                                                index
                                                            ]
                                                        }
                                                        placeholder={t(
                                                            "Enter field description",
                                                        )}
                                                        required
                                                        error={
                                                            errors[
                                                                `fieldDescription.${index}`
                                                            ]
                                                        }
                                                        disabled={
                                                            processing
                                                        }
                                                        onChange={(
                                                            e,
                                                        ) => {
                                                            const descriptions =
                                                                [
                                                                    ...data.fieldDescription,
                                                                ];

                                                            descriptions[
                                                                index
                                                            ] =
                                                                e.target.value;

                                                            setData(
                                                                "fieldDescription",
                                                                descriptions,
                                                            );

                                                            clearErrors(
                                                                `fieldDescription.${index}`,
                                                            );
                                                        }}
                                                    />
                                                </div>
                                            </div>
                                        ),
                                    )}
                                </div>
                            </div>

                            {/* Prompt */}
                            <div className="space-y-5">
                                <div>
                                    <h2 className="text-lg font-semibold">
                                        {t("Prompt")}
                                    </h2>

                                    <p className="text-sm text-muted-foreground">
                                        {t(
                                            "Define the prompt that will be used with this template.",
                                        )}
                                    </p>
                                </div>

                                <FormTextarea
                                    id="prompt"
                                    ref={promptRef}
                                    name="prompt"
                                    label={t("Prompt")}
                                    value={data.prompt}
                                    placeholder={t(
                                        "Enter your template prompt",
                                    )}
                                    required
                                    error={errors.prompt}
                                    disabled={processing}
                                    onChange={(e) => {
                                        setData(
                                            "prompt",
                                            e.target.value,
                                        );

                                        clearErrors(
                                            "prompt",
                                        );
                                    }}
                                />

                                {/* Prompt Variables */}
                                <div className="space-y-3">
                                    <p className="text-sm font-medium">
                                        {t(
                                            "Available Variables",
                                        )}
                                    </p>

                                    <div className="flex flex-wrap gap-2">
                                        {promptVariables.map(
                                            (
                                                variable,
                                            ) => (
                                                <Button
                                                    key={
                                                        variable.value
                                                    }
                                                    type="button"
                                                    variant="outline"
                                                    size="sm"
                                                    onClick={() =>
                                                        insertVariable(
                                                            variable.value,
                                                        )
                                                    }
                                                    disabled={
                                                        processing
                                                    }
                                                >
                                                    {t(
                                                        variable.label,
                                                    )}
                                                </Button>
                                            ),
                                        )}

                                        {data.aiInput.map(
                                            (
                                                value,
                                                index,
                                            ) => (
                                                <Button
                                                    key={
                                                        value
                                                    }
                                                    type="button"
                                                    variant="outline"
                                                    size="sm"
                                                    onClick={() =>
                                                        insertVariable(
                                                            value,
                                                        )
                                                    }
                                                    disabled={
                                                        processing
                                                    }
                                                >
                                                    {data
                                                        .fieldTitle[
                                                        index
                                                    ] ||
                                                        `${t(
                                                            "Input",
                                                        )} ${
                                                            index +
                                                            1
                                                        }`}
                                                </Button>
                                            ),
                                        )}
                                    </div>
                                </div>
                            </div>

                            {/* Submit */}
                            <div className="flex justify-end gap-3 pt-6">
                                <Button
                                    type="button"
                                    variant="outline"
                                    onClick={() =>
                                        router.get(
                                            route(
                                                "dashboard.admin.templates",
                                            ),
                                        )
                                    }
                                    disabled={processing}
                                >
                                    {t("Cancel")}
                                </Button>

                                <Button
                                    type="submit"
                                    disabled={processing}
                                >
                                    <LoadingSwap
                                        isLoading={
                                            processing
                                        }
                                    >
                                        {t(
                                            "Update Template",
                                        )}
                                    </LoadingSwap>
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </form>
            </div>
        </AppLayout>
    );
}