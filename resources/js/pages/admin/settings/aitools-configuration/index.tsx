import { type BreadcrumbItem } from "@/types";
import { Form, Head, usePage } from "@inertiajs/react";
import { useState } from "react";
import HeadingSmall from "@/components/heading-small";
import InputError from "@/components/input-error";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import SettingsLayout from "@/layouts/settings/layout";
import { toast } from "sonner";
import { SearchableSelect } from "@/components/admin/searchable-select";
import { LanguageMultiSelect } from "@/components/admin/language-multi-select";
import AppLayout from "@/layouts/app/app-layout";
import { useTranslation } from "react-i18next";
import FormInput from "@/components/admin/form-input";
import { systemSetting } from "@/types/admin";
import { LoadingSwap } from "@/components/ui/loading-swap";
import { textModels, imageModels, audioModels } from "@/data/ai-models";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Dashboard",
        href: route("dashboard.admin.overview"),
    },
    {
        title: "Settings",
        href: route("dashboard.admin.index.account"),
    },
    {
        title: "AI Tools Configuration",
        href: "#",
    },
];

export default function index() {
    const { config } = usePage<systemSetting>().props;

    const configValues = Object.fromEntries(
        config.map((item) => [item.config_key, item.config_value]),
    );

  

    const [settings, setSettings] = useState({
        openaiModel: configValues.openai_model || "",
        imageModel: configValues.image_model || "",
        textSpeechModel: configValues.text_speech_model || "",
        openaiApiKey: configValues.openai_api_key || "",
        shareContent: configValues.share_content || "",
        imageLength: configValues.image_length || "",
    });

    const updateSetting = (key: string, value: any) => {
        setSettings((prev) => ({
            ...prev,
            [key]: value,
        }));
    };

    const { t } = useTranslation();

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("System Settings")} />

            <SettingsLayout>
                <div className="space-y-6 max-w-[5xl]">
                    <HeadingSmall
                        title={t("AI Tools Configuration Settings")}
                        description={"General Website configuration settings"}
                    />

                    <Form
                        action={route("dashboard.admin.update.ai.settings")}
                        method="post"
                        resetOnSuccess={false}
                        className="space-y-6"
                        onSuccess={() => {
                            toast.success(
                                t("AI Settings Updated Successfully!"),
                            );
                        }}
                        onError={() => {
                            toast.error(t("Error updating settings"));
                        }}
                    >
                        {({
                            errors,
                            processing,
                            recentlySuccessful,
                            clearErrors,
                            setError,
                        }) => (
                            <div>
                                <div className="grid grid-cols-1 gap-6 md:grid-cols-2 items-start">
                                    <SearchableSelect
                                        label={t("Open AI Text Model")}
                                        value={settings.openaiModel}
                                        onChange={(value) =>
                                            updateSetting("openaiModel", value)
                                        }
                                        options={textModels.map((model) => ({
                                            value: model.value,
                                            label: model.label,
                                        }))}
                                        placeholder={t("Select text model")}
                                        searchPlaceholder={t(
                                            "Search text model...",
                                        )}
                                        name="ai_model"
                                        error={errors.ai_model}
                                    />

                                    <SearchableSelect
                                        label={t("Open AI Image Model")}
                                        value={settings.imageModel}
                                        onChange={(value) =>
                                            updateSetting("imageModel", value)
                                        }
                                        options={imageModels.map((model) => ({
                                            value: model.value,
                                            label: model.label,
                                        }))}
                                        placeholder={t("Select image model")}
                                        searchPlaceholder={t(
                                            "Search image model...",
                                        )}
                                        name="image_model"
                                        error={errors.image_model}
                                    />

                                    <SearchableSelect
                                        label={t(
                                            "Open AI Text to Speech Model",
                                        )}
                                        value={settings.textSpeechModel}
                                        onChange={(value) =>
                                            updateSetting(
                                                "textSpeechModel",
                                                value,
                                            )
                                        }
                                        options={audioModels.map((model) => ({
                                            value: model.value,
                                            label: model.label,
                                        }))}
                                        placeholder={t(
                                            "Select text to speech model",
                                        )}
                                        searchPlaceholder={t(
                                            "Search text to speech model...",
                                        )}
                                        name="text_speech_model"
                                        error={errors.text_speech_model}
                                    />

                                    <FormInput
                                        id="openai_api_key"
                                        name="openai_api_key"
                                        type="text"
                                        label={t("OpenAI API Key")}
                                        required
                                        defaultValue={settings.openaiApiKey}
                                        placeholder={t("Eg: sk-****************")}
                                        error={errors.openai_api_key}
                                        onChange={() => clearErrors("openai_api_key")}
                                    />

                                    <FormInput
                                        id="share_content"
                                        name="share_content"
                                        type="number"
                                        min={0}
                                        label={t("Maximum Words Length")}
                                        required
                                        defaultValue={settings.shareContent || 0}
                                        placeholder={t("Maximum Length (Eg: 1200)")}
                                        error={errors.share_content}
                                        onChange={() => clearErrors("share_content")}
                                    />

                                    <FormInput
                                        id="image_length"
                                        name="image_length"
                                        type="number"
                                        label={t("Maximum Images Options")}
                                        required
                                        min={0}
                                        defaultValue={settings.imageLength || 0}
                                        placeholder={t("Images Options (Eg: 3)")}
                                        error={errors.image_length}
                                        onChange={() => clearErrors("image_length")}
                                    />
                                </div>

                                <div className="flex items-center mt-4 gap-4">
                                    <Button type="submit" disabled={processing}>
                                        <LoadingSwap isLoading={processing}>
                                            Update
                                        </LoadingSwap>
                                    </Button>
                                </div>
                            </div>
                        )}
                    </Form>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
