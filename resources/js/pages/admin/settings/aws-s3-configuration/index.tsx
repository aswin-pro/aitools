import { type BreadcrumbItem } from "@/types";
import { Form, Head, usePage } from "@inertiajs/react";
import { useState } from "react";
import { toast } from "sonner";

import AppLayout from "@/layouts/app/app-layout";
import SettingsLayout from "@/layouts/settings/layout";
import HeadingSmall from "@/components/heading-small";
import FormInput from "@/components/admin/form-input";
import { SearchableSelect } from "@/components/admin/searchable-select";
import { Button } from "@/components/ui/button";
import { LoadingSwap } from "@/components/ui/loading-swap";

import { useTranslation } from "react-i18next";

type S3Settings = {
    aws_enable: string;
    access_key: string;
    secret_key: string;
    default_region: string;
    bucket: string;
    end_point: string;
};

type S3PageProps = {
    s3Settings: S3Settings;
};

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
        title: "AWS S3 Configuration",
        href: "#",
    },
];


export default function Index() {
    const { s3Settings } = usePage<S3PageProps>().props;

    const { t } = useTranslation();

    const [settings, setSettings] = useState({
        awsEnable: String(s3Settings.aws_enable || "false"),
        accessKey: s3Settings.access_key || "",
        secretKey: s3Settings.secret_key || "",
        defaultRegion: s3Settings.default_region || "",
        bucket: s3Settings.bucket || "",
        endPoint: String(s3Settings.end_point || "false"),
    });

    const updateSetting = (key: string, value: string) => {
        setSettings((prev) => ({
            ...prev,
            [key]: value,
        }));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={t("AWS S3 Configuration Settings")} />

            <SettingsLayout>
                <div className="max-w-[5xl] space-y-6">
                    <HeadingSmall
                        title={t("AWS S3 Configuration Settings")}
                        description={t(
                            "Configure AWS S3 storage settings for your application.",
                        )}
                    />

                    <Form
                        action={route("dashboard.admin.update.awss3.settings")}
                        method="post"
                        resetOnSuccess={false}
                        className="space-y-6"
                        onSuccess={() => {
                            toast.success(
                                t(
                                    "AWS S3 configuration settings updated successfully!",
                                ),
                            );
                        }}
                        onError={() => {
                            toast.error(t("Error updating AWS S3 settings"));
                        }}
                    >
                        {({ errors, processing, clearErrors }) => (
                            <div>
                                <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-2 items-start">
                                    <SearchableSelect
                                        label={t("AWS")}
                                        value={settings.awsEnable}
                                        onChange={(value) => {
                                            updateSetting("awsEnable", value);
                                            clearErrors("aws_enable");
                                        }}
                                        options={[
                                            {
                                                value: "true",
                                                label: t("Enable"),
                                            },
                                            {
                                                value: "false",
                                                label: t("Disable"),
                                            },
                                        ]}
                                        placeholder={t("Select AWS status")}
                                        searchable={false}
                                        name="aws_enable"
                                        error={errors.aws_enable}
                                    />

                                    <FormInput
                                        id="access_key"
                                        name="access_key"
                                        type="text"
                                        label={t("Access Key ID")}
                                        defaultValue={settings.accessKey}
                                        placeholder={t(
                                            "Access Key ID (Eg: AKI**************)",
                                        )}
                                        error={errors.access_key}
                                        onChange={() =>
                                            clearErrors("access_key")
                                        }
                                    />

                                    <FormInput
                                        id="secret_key"
                                        name="secret_key"
                                        type="text"
                                        label={t("Secret Access Key")}
                                        defaultValue={settings.secretKey}
                                        placeholder={t("Secret Access Key")}
                                        error={errors.secret_key}
                                        onChange={() =>
                                            clearErrors("secret_key")
                                        }
                                    />

                                    <FormInput
                                        id="default_region"
                                        name="default_region"
                                        type="text"
                                        label={t("Access Region")}
                                        required
                                        defaultValue={settings.defaultRegion}
                                        placeholder={t(
                                            "Default Region (Eg: ap-east-1)",
                                        )}
                                        error={errors.default_region}
                                        onChange={() =>
                                            clearErrors("default_region")
                                        }
                                    />

                                    <FormInput
                                        id="bucket"
                                        name="bucket"
                                        type="text"
                                        label={t("Bucket")}
                                        required
                                        defaultValue={settings.bucket}
                                        placeholder={t(
                                            "Bucket (Eg: my-bucket)",
                                        )}
                                        error={errors.bucket}
                                        onChange={() => clearErrors("bucket")}
                                    />

                                    <SearchableSelect
                                        label={t("Use Path Style Endpoint")}
                                        value={settings.endPoint}
                                        onChange={(value) => {
                                            updateSetting("endPoint", value);
                                            clearErrors("end_point");
                                        }}
                                        options={[
                                            {
                                                value: "true",
                                                label: "True",
                                            },
                                            {
                                                value: "false",
                                                label: "False",
                                            },
                                        ]}
                                        placeholder={t(
                                            "Select endpoint option",
                                        )}
                                        searchPlaceholder={t(
                                            "Search endpoint option...",
                                        )}
                                        searchable={false}

                                        name="end_point"
                                        error={errors.end_point}
                                    />
                                </div>

                                <div className="mt-6 ">
                                    <Button type="submit" disabled={processing}>
                                        <LoadingSwap isLoading={processing}>
                                            {t("Update")}
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
